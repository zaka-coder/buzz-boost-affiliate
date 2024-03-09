<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    //

    public function checkout($id, $order_id = null)
    {
        $user = auth()->user();
        // dd($user->profile);
        if($user->profile == null){
            return redirect()->route('buyer.profile.create');
        }

        $product = Product::findOrFail($id);

        if($product->status == 'sold'){
            return redirect()->back()->with('error', 'Product is already sold');
        }

        $order = null;
        if($order_id != null){
            $order = $user->orders()->where('id', $order_id)->first();
            // dd($order);
        }


        $store = $product->store;
        $shipping = $product->productShipping;
        // get user last shipping address where product id is equal to $product->id
        $shippingAddress = ShippingAddress::where('product_id', $id)->where('user_id', $user->id)->latest()->first();

        return view('buyer.cart.checkout', compact('product', 'store', 'shipping', 'shippingAddress', 'order'));
    }

}
