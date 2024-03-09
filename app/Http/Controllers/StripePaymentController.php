<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingPreference;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\FlareClient\Http\Exceptions\BadResponse;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{

    public function store(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $store = Store::findOrFail($request->store_id);
            $paymentMethod = $store->paymentMethods()->where('name', 'stripe')->where('status', 1)->first();
            // $stripe = new StripeClient(env('STRIPE_SECRET'));
            $stripe = new StripeClient(decrypt($paymentMethod->secret));

            $order = Order::find($request->order_id);
            // $product = Product::findOrFail($request->product_id);
            $products = Product::whereIn('id', $request->product_id)->get();
            $total = 0;
            $insurance = $request->insurance;
            $shipping_cost = 0;
            $quantity = 1;


            if ($store->shippings->count() > 0 && $request->shipping_provider_id != 0) {

                $shipping = ShippingPreference::where('id', $request->shipping_provider_id)->where('store_id', $store->id)->first();
                if ($shipping) {
                    $shipping_cost = $shipping->domestic_shipping_fee_per_item;
                }
            }

            if ($order == null) {
                // Check if the product is already sold
                if ($products->count() == 1 && $products[0]->status == 'sold') {
                    return redirect()->route('buyer.cart')->withError('The product has already been sold.');
                }

                foreach ($products as $product) {
                    // $price = $product->productPricing->buy_it_now_price;
                    // $total = ($price * $quantity) + $shipping_cost + $insurance;
                    $total = $product->productPricing->buy_it_now_price + $total;
                }
                $total = $total + $insurance + $shipping_cost;
            } else {
                $total = $order->total + $insurance + $shipping_cost;
            }

            $response = $stripe->paymentIntents->create([
                'amount' => $total * 100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method,
                'description' => 'Payment for item(s) purchased from ' . $store->name . ' store',
                'confirm' => true,
                'receipt_email' => $request->email,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                // 'payment_method_types' => ['card'],
            ]);

            // dd($response);
            if ($response->status == 'succeeded') {
                // save order to database
                try {
                    DB::beginTransaction();
                    $transaction = new Transaction();
                    $transaction->user_id = $request->user_id;
                    $transaction->vendor_payment_id = $response->id;
                    $transaction->payment_gateway_id = 'Stripe';
                    $transaction->status   = 'completed';
                    $transaction->store_id   = $store->id;
                    $transaction->product_id   = $products[0]->id;
                    $transaction->amount = $total;
                    $transaction->save();

                    // saving order to db
                    if ($order == null) {
                        $createdOrder = Order::create([
                            'user_id' => $request->user_id,
                            'store_id' => $store->id,
                            'postal_insurance' => $insurance,
                            'shipping_cost' => $shipping_cost,
                            'shipping_provider_id' => $request->shipping_provider_id,
                            'total' => $total,
                            'status' => 'paid awaiting shippment',
                            'payment_status' => 'paid',
                            'payment_method' => 'Stripe',
                            'transaction_id' => $transaction->id,
                            'vendor_order_id' => $response->id,
                            'shipping_address' => $request->shipping_address,
                            'shipping_city' => $request->shipping_city,
                            'shipping_country' => $request->shipping_country,
                            'shipping_state' => $request->shipping_state,
                            'shipping_postal_code' => $request->shipping_postal_code,
                            'shipping_name' => $request->shipping_name,
                            'shipping_email' => $request->shipping_email,
                            'shipping_phone' => $request->shipping_phone,
                            'won_via' => 'cart',
                        ]);
                        if ($createdOrder) {
                            // also create order items
                            foreach ($products as $product) {
                                $orderItem = new OrderItem();
                                $orderItem->order_id = $createdOrder->id;
                                $orderItem->product_id = $product->id;
                                $orderItem->quantity = 1;
                                $orderItem->price = $product->productPricing->buy_it_now_price;
                                $orderItem->save();
                            }

                            $transaction->order_id = $createdOrder->id;
                            $transaction->save();
                        }
                    } else {
                        $order->update([
                            'total' => $total,
                            'postal_insurance' => $insurance,
                            'shipping_cost' => $shipping_cost,
                            'shipping_provider_id' => $request->shipping_provider_id,
                            'status' => 'paid awaiting shippment',
                            'payment_status' => 'paid',
                            'payment_method' => 'Stripe',
                            'transaction_id' => $transaction->id,
                            'vendor_order_id' => $response->id,
                            'shipping_address' => $request->shipping_address,
                            'shipping_city' => $request->shipping_city,
                            'shipping_country' => $request->shipping_country,
                            'shipping_state' => $request->shipping_state,
                            'shipping_postal_code' => $request->shipping_postal_code,
                            'shipping_name' => $request->shipping_name,
                            'shipping_email' => $request->shipping_email,
                            'shipping_phone' => $request->shipping_phone,
                        ]);

                        $transaction->order_id = $order->id;
                        $transaction->save();
                    }

                    foreach ($products as $product) {
                        // update product is_sold
                        $product->is_sold = true;
                        $product->status = 'sold';
                        $product->save();

                        if ($user->cart && $user->cart->products->contains($product->id)) {
                            // remove product from cart
                            $user->cart->products()->detach($product->id);
                            // update cart total items
                            $user->cart->total_items = $user->cart->total_items - 1;
                            $user->cart->save();
                        }
                    }

                    DB::commit();

                    foreach ($products as $product) {

                        if ($store?->emailNotifications?->payment_received) {
                            // send notification to seller
                            $store->user->notify(new \App\Notifications\SellerPaymentNotification($product, $user, 'Stripe'));
                        }

                        // send notification to buyer
                        $user->notify(new \App\Notifications\BuyerPaymentNotification($product, 'Stripe'));

                        Notification::create([
                            'user_id' => $store->user_id,
                            'title' => 'Your item' . $product->name . 'has been sold and the payment has been received in your Stripe account.',
                            'is_read' => 0,
                        ]);
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    // dd($e);
                }
                // redirect to thank you page
                return redirect()->route('buyer.orders.thanks');
            } else {
                throw new Exception("There was a problem processing your payment", 1);
            }
        } catch (CardException $th) {
            throw new Exception("There was a problem processing your payment", 1);
        }

        return redirect()->route('cart')->withError('Something went wrong! Please try again.');
    }
}
