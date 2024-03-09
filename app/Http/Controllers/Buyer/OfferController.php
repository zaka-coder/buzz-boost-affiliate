<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index($status = null)
    {

        $offers = Offer::where('user_id', auth()->user()->id)->get();
        // filter offers by status
        if ($status) {
            $offers = $offers->where('status', $status);
        } else {
            $offers = $offers->where('status', 'pending');
        }
        return view('buyer.offers.index', compact('offers', 'status'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->profile){
            return response([
                'success' => false,
                'message' => 'Please complete your profile first.',
            ]);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offer_value' => 'required|numeric',
            'validity' => 'required|numeric',
        ]);

        $product = Product::findOrFail($request->product_id);
        $store = $product->store;
        if($store->minimum_offer != null) {
            $acceptedValue = $product->productPricing->buy_it_now_price * $store->minimum_offer / 100;
            if($request->offer_value < $acceptedValue) {
                return response([
                    'success' => false,
                    'message' => 'Offer value should not be less than '.$store->minimum_offer.'% of the item price',
                ]);
            }
        }


        $offer = Offer::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->user()->id,
            'offer_value' => $request->offer_value,
            'validity' => $request->validity,
        ]);

        if($offer) {
            if($store?->emailNotifications?->offer_made){
                $store?->user?->notify(new \App\Notifications\OfferMadeNotification($product));
            }

            Notification::create([
                'user_id' => $store?->user_id,
                'title' => 'An offer has been made on your item',
                'is_read' => 0
            ]);
            
            return response([
                'success' => true,
                'message' => 'Offer placed successfully',
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Something went wrong',
            ]);
        }

        // return redirect()->back()->with('success', 'Bid placed successfully');
    }

    public function itemOffers($id)
    {
        $offers = Offer::where('product_id', $id)->orderBy('id', 'desc')->get();
        foreach ($offers as $offer) {
            $offer->created = $offer->created_at->diffForHumans();
            $offer->validity = $offer->validity . ' Days';
            $offer->offer_value = number_format($offer->offer_value, 2);
            $offer->userName = $offer->user->name;
        }
        return response()->json($offers);

    }
}
