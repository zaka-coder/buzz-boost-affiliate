<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\AcceptOfferNotification;
use App\Notifications\DeclinedOfferNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function index($status = null)
    {
        // get all offers which are placed on the users store products
        $store = auth()->user()->store; // get user store
        $products = $store->products; // get all store products
        $offers = $products->pluck('offers')->flatten(); // get all offers from all products
        // filter offers by status
        if ($status) {
            $offers = $offers->where('status', $status);
        } else {
            $offers = $offers->where('status', 'pending');
        }

        return view('seller.offers.index', compact('offers', 'status'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $offer = Offer::findOrFail($id);
        // dd($offer);

        $offer->update([
            'status' => $request->status
        ]);


        if ($request->status == 'accepted') {
            $offer->product->update([
                'is_sold' => true,
            ]);

            // create order for buyer
            // $product = Product::findOrFail($offer->product_id);
            $store = Auth::user()->store;
            // $shipping_cost = $store->shippings->first()->domestic_shipping_fee_per_item; // for single item
            // $price = $product->productPricing->buy_it_now_price ?? 0;
            $price = $offer->offer_value;
            $quantity = 1;
            $insurance = 0;
            $total = 0;
            $shipping_cost = 0;

            $total = ($price * $quantity) + $shipping_cost + $insurance;

            // save order to database
            $order = Order::create([
                'user_id' => $offer->user_id,
                'store_id' => $store->id,
                'postal_insurance' => $insurance,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'won_via' => 'offer',
            ]);

            if ($order) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $offer->product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                // send email notification to buyer
                $offer->user->notify(new AcceptOfferNotification($offer));

                // save notification to database
                Notification::create([
                    'user_id' => $offer->user_id,
                    'title' => 'Your offer has been accepted for the item: ' . $offer->product->name,
                    'is_read' => 0,
                ]);
            }
        } elseif($request->status == 'declined') {
            // send email notification to buyer
            $offer->user->notify(new DeclinedOfferNotification($offer));

            // save notification to database
            Notification::create([
                'user_id' => $offer->user_id,
                'title' => 'Your offer has been declined for the item: ' . $offer->product->name,
                'is_read' => 0,
                // 'source' =>
            ]);
        }
        // return response()->json(['message' => 'Offer status updated successfully']);
        return back()->with('success', 'Offer status updated successfully');
    }
}
