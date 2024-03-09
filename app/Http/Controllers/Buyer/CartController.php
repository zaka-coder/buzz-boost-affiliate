<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\Store;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $productsByStore = null;
        $cart = auth()->user()->cart;

        if ($cart) {
            $productsByStore = $cart->products->groupBy('store_id');
        }

        // extract stores Ids from products
        // $stores = $productsByStore->keys();

        return view('buyer.cart.cart-v2', compact('productsByStore'));
    }

    public function store($id)
    {
        // check if user is authenticated
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Not found',
            ]);
        }

        // check if user has cart
        if (auth()->user()->cart) {
            $cart = auth()->user()->cart;
            // check if product is already in cart
            if ($cart->products->contains('id', $product->id)) {
                // Product is already in the cart
                return response()->json([
                    'success' => false,
                    'message' => 'Already in cart',
                ]);

            } else {
                // Product is not in the cart
                // $cart->total += $product->productPricing->buy_it_now_price ?? 0;
                $cart->total_items++;
                $cart->save();

                $cart->products()->attach($product->id);
                return response()->json([
                    'success' => true,
                    'message' => 'Item added to cart!',
                    'cart' => $cart
                ]);
            }
        } else {
            // create new cart
            $cart = Cart::create([
                'user_id' => auth()->user()->id,
                // 'total' => $product->productPricing->buy_it_now_price ?? 0,
                'total_items' => 1
            ]);
            $cart->products()->attach($product->id);
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart!',
                'cart' => $cart
            ]);
        }
    }

    public function create(Request $request, $order = null)
    {
        // dd($request->all());
        $user = auth()->user();
        $insurance = $request->insurance;
        $selectedShipping = $request->shipping_provider;
        $products = Product::whereIn('id', $request->checkedItems)->get();

        // calculate total price
        $total = 0;
        foreach ($products as $product) {
            $total += $product->productPricing->buy_it_now_price;
        }

        $product = $products->first();
        $store = $products->first()->store;

        return view('buyer.cart.checkout-v2', compact('store', 'order', 'products', 'insurance', 'selectedShipping', 'total'));
    }

    public function delete($id)
    {
        $cart = auth()->user()->cart;
        $cart->products()->detach($id);
        $cart->total_items = $cart->total_items - 1;
        $cart->save();

        return back()->with('success', 'Item removed from cart.');
    }
}
