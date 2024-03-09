<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index($status = null)
    {
        $orders = auth()->user()->orders;
        // filter orders by status
        if ($status) {
            $orders = $orders->where('status', $status);
        }
        return view('buyer.orders.win-history', compact('orders', 'status'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'store_id' => 'required',
            'insurance' => 'required',
        ]);

        // $orders = auth()->user()->orders;
        // if($orders){
        //     foreach($orders as $order){
        //         if($order->orderItems()->where('product_id', $request->product_id)->exists()){
        //             return response([
        //                 'success' => false,
        //                 'message' => 'Order already exists for this product',
        //             ]);
        //         }
        //     }
        // }

        $product = Product::findOrFail($request->product_id);
        $store = Store::findOrFail($request->store_id);
        // $shipping_cost = $store->shippings->first()->domestic_shipping_fee_per_item; // for single item
        $price = $product->productPricing->buy_it_now_price;
        $quantity = 1;
        $insurance = 0;
        $total = 0;
        $shipping_cost = 0;
        $shipping_cost = $request->shipping_cost;


        if($request->insurance == 'true'){
            $insurance = $store->insurance;
        }

        $total = ($price * $quantity) + $shipping_cost + $insurance;

        $request->merge(['user_id' => auth()->user()->id]); // set user id in request inputs array
        $request->merge(['postal_insurance' => $insurance ]); // set postal insurance in request inputs array
        $request->merge(['shipping_cost' => $shipping_cost]);
        $request->merge(['total' => $total]);
        $request->merge(['status' => 'pending']);

        // save order to database
        $order = Order::create($request->all());

        if($order){
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $request->product_id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again!'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully!',
        ]);
    }

    public function thanks()
    {
        return view('buyer.orders.thanks');
    }

}
