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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Exception;
use Illuminate\Support\Facades\Auth;

class PaypalPaymentController extends Controller
{
    // protected $config;

    public function __construct()
    {
        // $this->config = [
        //     'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
        //     'sandbox' => [
        //         'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        //         'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        //         // 'app_id'            => 'APP-80W284485P519543T',
        //         'app_id'            => '',
        //     ],
        //     'live' => [
        //         'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
        //         'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        //         'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
        //     ],

        //     'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
        //     'currency'       => env('PAYPAL_CURRENCY', 'USD'),
        //     'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
        //     'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
        //     'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', false), // Validate SSL when creating api client.
        // ];
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $store = Store::findOrFail($data['store_id']);
        $paypalMethod = $store->paymentMethods()->where('name', 'paypal')->where('status', 1)->first();

        $config = [
            'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id'         => $paypalMethod->key, // env('PAYPAL_SANDBOX_CLIENT_ID', ''),
                'client_secret'     => decrypt($paypalMethod->secret), // env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
                // 'app_id'            => 'APP-80W284485P519543T',
                'app_id'            => '',
            ],
            'live' => [
                'client_id'         => $paypalMethod->key, // env('PAYPAL_LIVE_CLIENT_ID', ''),
                'client_secret'     => decrypt($paypalMethod->secret), // env('PAYPAL_LIVE_CLIENT_SECRET', ''),
                'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
            ],

            'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => env('PAYPAL_CURRENCY', 'USD'),
            'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
            'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', false), // Validate SSL when creating api client.
        ];


        $provider = \PayPal::setProvider();

        // $provider->setApiCredentials(config('paypal')); // default
        $provider->setApiCredentials($config);
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $order = Order::find($data['order_id']);
        // $product = Product::findOrFail($data['product_id']);
        $productIds = explode(',', $data['product_ids']);
        $products = Product::whereIn('id', $productIds)->get();

        $total = 0;
        $insurance = 0;
        $shipping_cost = 0;
        $quantity = 1;

        if ($data['insurance'] == 'true') {
            $insurance = $store->insurance;
        }

        // if ($product->productShipping->shipping_type == 'normal') {
        //     if ($store->shippings->count() == 0) {
        //         $shipping_cost = 0;
        //     } else {
        //         $shipping_cost = ShippingPreference::where('id', $data['shipping_provider_id'])->where('store_id', $store->id)->first()->domestic_shipping_fee_per_item;
        //     }
        // }
        if ($store->shippings->count() > 0 && $data['shipping_provider_id'] != 0) {

            $shipping = ShippingPreference::where('id', $data['shipping_provider_id'])->where('store_id', $store->id)->first();
            if ($shipping) {
                $shipping_cost = $shipping->domestic_shipping_fee_per_item;
            }
        }

        // if ($order == null) {
        //     // Check if the product is already sold
        //     if ($product->status == 'sold') {
        //         return response()->json(['message' => 'The product is already sold.'], 400);
        //     }

        //     $price = $product->productPricing->buy_it_now_price;


        //     // dd($insurance);
        //     $total = ($price * $quantity) + $shipping_cost + $insurance;
        // } else {
        //     $total = $order->total + $insurance + $shipping_cost;
        // }
        if ($order == null) {
            // Check if the product is already sold
            if ($products->count() == 1 && $products[0]->status == 'sold') {
                return response()->json(['message' => 'The product is already sold.'], 400);
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

        $description = 'Payment for item purchased from ' . $store->name . ' store';

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $total
                        // "value" => $data['shipping_provider_id']
                    ],
                    'description' => $description
                ]
            ],
        ]);

        return response()->json($order);
    }

    public function capture(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $orderId = $data['orderId'];

        $user = User::find($data['user_id']);
        $store = Store::findOrFail($data['store_id']);
        $paypalMethod = $store->paymentMethods()->where('name', 'paypal')->where('status', 1)->first();

        $config = [
            'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id'         => $paypalMethod->key, // env('PAYPAL_SANDBOX_CLIENT_ID', ''),
                'client_secret'     => decrypt($paypalMethod->secret), // env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
                // 'app_id'            => 'APP-80W284485P519543T',
                'app_id'            => '',
            ],
            'live' => [
                'client_id'         => $paypalMethod->key, // env('PAYPAL_LIVE_CLIENT_ID', ''),
                'client_secret'     => decrypt($paypalMethod->secret), // env('PAYPAL_LIVE_CLIENT_SECRET', ''),
                'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
            ],

            'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => env('PAYPAL_CURRENCY', 'USD'),
            'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
            'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', false), // Validate SSL when creating api client.
        ];

        // Init PayPal
        $provider = \PayPal::setProvider();
        $provider->setApiCredentials($config);
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);
        $result = $provider->capturePaymentOrder($orderId);

        $order = Order::find($data['order_id']);
        // $product = Product::findOrFail($data['product_id']);
        $productIds = explode(',', $data['product_ids']);
        $products = Product::whereIn('id', $productIds)->get();

        $total = 0;
        $insurance = 0;
        $shipping_cost = 0;
        $quantity = 1;

        if ($data['insurance'] == 'true') {
            $insurance = $store->insurance;
        }

        if ($store->shippings->count() > 0 && $data['shipping_provider_id'] != 0) {

            $shipping = ShippingPreference::where('id', $data['shipping_provider_id'])->where('store_id', $store->id)->first();
            if ($shipping) {
                $shipping_cost = $shipping->domestic_shipping_fee_per_item;
            }
        }

        if ($order == null) {
            // Check if the product is already sold
            if ($products->count() == 1 && $products[0]->status == 'sold') {
                return response()->json(['message' => 'The product is already sold.'], 400);
            }

            foreach ($products as $product) {
                $total = $product->productPricing->buy_it_now_price + $total;
            }
            $total = $total + $insurance + $shipping_cost;
        } else {
            $total = $order->total + $insurance + $shipping_cost;
        }

        try {
            DB::beginTransaction();
            if ($result['status'] === "COMPLETED") {
                $transaction = new Transaction();
                $transaction->user_id = $data['user_id'];
                $transaction->vendor_payment_id = $orderId;
                $transaction->payment_gateway_id = 'PayPal';
                $transaction->status   = 'completed';
                $transaction->store_id   = $store->id;
                $transaction->product_id   = $products[0]->id;
                $transaction->amount = $total;
                $transaction->save();

                if ($order == null) {
                    // saving order to db
                    $createdOrder = Order::create([
                        'user_id' => $data['user_id'],
                        'store_id' => $store->id,
                        'postal_insurance' => $insurance,
                        'shipping_cost' => $shipping_cost,
                        'shipping_provider_id' => $data['shipping_provider_id'],
                        'total' => $total,
                        'status' => 'paid awaiting shippment',
                        'payment_status' => 'paid',
                        'payment_method' => 'PayPal',
                        'transaction_id' => $transaction->id,
                        'vendor_order_id' => $orderId,
                        'shipping_address' => $data['shipping_address'],
                        'shipping_city' => $data['shipping_city'],
                        'shipping_country' => $data['shipping_country'],
                        'shipping_state' => $data['shipping_state'],
                        'shipping_postal_code' => $data['shipping_postal_code'],
                        'shipping_name' => $data['shipping_name'],
                        'shipping_email' => $data['shipping_email'],
                        'shipping_phone' => $data['shipping_phone'],
                        'won_via' => 'cart',
                    ]);
                    if ($createdOrder) {
                        // also create order items
                        // OrderItem::create([
                        //     'order_id' => $createdOrder->id,
                        //     'product_id' => $data['product_id'],
                        //     'quantity' => $quantity,
                        //     'price' => $price,
                        // ]);
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
                    // update order
                    $order->update([
                        'total' => $total,
                        'postal_insurance' => $insurance,
                        'shipping_cost' => $shipping_cost,
                        'shipping_provider_id' => $data['shipping_provider_id'],
                        'status' => 'paid awaiting shippment',
                        'payment_status' => 'paid',
                        'payment_method' => 'PayPal',
                        'transaction_id' => $transaction->id,
                        'vendor_order_id' => $orderId,
                        'shipping_address' => $data['shipping_address'],
                        'shipping_city' => $data['shipping_city'],
                        'shipping_country' => $data['shipping_country'],
                        'shipping_state' => $data['shipping_state'],
                        'shipping_postal_code' => $data['shipping_postal_code'],
                        'shipping_name' => $data['shipping_name'],
                        'shipping_email' => $data['shipping_email'],
                        'shipping_phone' => $data['shipping_phone'],
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
                        $store->user->notify(new \App\Notifications\SellerPaymentNotification($product, $user, 'PayPal'));
                    }

                    // send notification to buyer
                    $user->notify(new \App\Notifications\BuyerPaymentNotification($product, 'PayPal'));

                    Notification::create([
                        'user_id' => $store->user_id,
                        'title' => 'Your item' . $product->name . 'has been sold and the payment has been received in your PayPal account.',
                        'is_read' => 0,
                    ]);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            // dd($e);
        }
        return response()->json($result);
    }

    // for sending amount from admin to seller account
    public function store(Request $request)
    {
        $provider = \PayPal::setProvider();

        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $product = Product::findOrFail($request->product_id);
        $price = $product->productPricing->buy_it_now_price;

        // Payout to store owner's email

        // $payout = $provider->createBatchPayout([
        //     'sender_batch_header' => [
        //         'sender_batch_id' => uniqid(),
        //         'email_subject' => 'Payment for sold item',
        //     ],
        //     'items' => [
        //         [
        //             'recipient_type' => 'EMAIL',
        //             'amount' => [
        //                 'value' => $total,
        //                 'currency' => 'USD',
        //             ],
        //             'receiver' => 'sb-o5s2229395974@personal.example.com', // Replace with the store owner's email
        //             'note' => 'Payment for sold item',
        //         ],
        //     ],
        // ]);

        $data = json_decode('{
          "sender_batch_header": {
            "sender_batch_id": "Payouts_2023_' . time() . '",
            "email_subject": "You have a payout form GemsHarbor.com!",
            "email_message": "You have received a payout! Thanks for using our service!"
          },
          "items": [
            {
              "recipient_type": "EMAIL",
              "amount": {
                "value": "' . $price . '",
                "currency": "USD"
              },
              "note": "Thanks for your patronage!",
              "sender_item_id": "' . $product->id . '-' . time() . '",
              "receiver": "sb-o5s2229395974@personal.example.com",
              "notification_language": "en-US"
            }
          ]
        }', true);

        $response = $provider->createBatchPayout($data);
        dd($response);
        return redirect()->route('dashboard')
            ->with('success', 'PayPal payment sent successfully.');
    }
}
