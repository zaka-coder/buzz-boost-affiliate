<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getCartSingleProduct($id)
    {
        $product = Product::find($id);
        $category = $product->category;
        $store = $product->store;
        $store->load('paymentMethods');
        $pricing = $product->productPricing;
        $listing = $product->productListing;
        $shippingProviders = $store?->shippings ?? [];
        $shipping_price = $store?->shippings?->first()->domestic_shipping_fee_per_item ?? 0;
        $shippingProviderId = $store?->shippings?->first()->id ?? 0;
        $insurance = $store->insurance;
        $shippingType = 'normal';
        if ($product->productShipping->shipping_type == 'custom') {
            $shipping_price = $product->productShipping->custom_shipping_cost;
            $shippingType = 'custom';
        }
        return response()->json([
            'status' => 'success',
            'product' => $product,
            'category' => $category,
            'store' => $store,
            'pricing' => $pricing,
            'listing' => $listing,
            'shippingProviders' => $shippingProviders,
            'shipping_price' => $shipping_price,
            'shippingProviderId' => $shippingProviderId,
            'shippingType' => $shippingType,
        ]);
    }

    public function checkIfUserIsBlocked($productId)
    {
        return response()->json($productId);
        $product = Product::find($productId);
        $store = $product->store;
        $store->load('blocked_users');
        if ($store->blocked_users->contains(auth()->user()->id)) {
            return response()->json([
                'blocked' => true
            ]);
        } else {
            return response()->json([
                'blocked' => false
            ]);
        }
    }
}
