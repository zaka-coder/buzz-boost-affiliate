<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Traits\RatingTrait;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use RatingTrait;

    public function getStoreShippingPreferences($id)
    {
        $store = \App\Models\Store::findOrFail($id);

        $shippingPreferences = \App\Models\ShippingPreference::where('store_id', $store->id)->get();

        // Extracting an array of shipping_provider_id values from $shippings
        $shippingProviderIds = $shippingPreferences->pluck('shipping_provider_id')->toArray();

        // Retrieving shipping providers based on the extracted shipping_provider_id values
        $shippingProviders = \App\Models\ShippingProvider::whereIn('id', $shippingProviderIds)->get();

        // Merging shipping prices with corresponding shipping providers
        $mergedData = [];
        foreach ($shippingPreferences as $shipping) {
            $shippingProviderId = $shipping->shipping_provider_id;

            // Find the corresponding shipping provider
            $provider = $shippingProviders->where('id', $shippingProviderId)->first();

            if ($provider) {
                // Merge shipping information
                $mergedData[] = [
                    'shipping_preference' => $shipping,
                    'shipping_provider' => $provider,
                    'shipping_price' => $shipping->domestic_shipping_fee_per_item,
                ];
            }
        }


        // $shipping_price = 0;

        return response()->json([
            'status' => 'success',
            'shippingProviders' => $shippingProviders,
            'shippingPreferences' => $shippingPreferences,
            'data' => $mergedData,
            // 'shipping_price' => $shipping_price,
            // 'shippingType' => $shippingType,
        ]);
    }

    public function getCountryStores(Request $request)
    {
        $stores = null;
        if ($request->countries == null) {
            // get stores which are approved with products and products with feedbacks
            $stores = Store::with('products')->where('approved', 1)->get();
            // $stores = Store::with(['products', 'products.feedbacks'])->get();

        } else {
            $stores = Store::with(['products', 'products.feedbacks'])->where('approved', 1)->whereIn('country', $request->countries)->get();
        }

        foreach ($stores as $store) {
            $products = $store->products;
            $feedbacks = collect([]);
            foreach ($products as $product) {
                $feedbacks = $feedbacks->merge($product->feedbacks);
            }

            $ratings = 0;
            if (!$feedbacks->isEmpty()) {
                $ratings = $this->ratingPercentage($feedbacks); // rating percentage from RatingTrait
            }

            $store->ratingPercent = number_format($ratings, 1);
        }

        return response()->json([
            'stores' => $stores,
        ]);
    }

    public function getStoreSingleShippingPreference($id, $spId)
    {
        $store = \App\Models\Store::findOrFail($id);
        $shippingPreference = \App\Models\ShippingPreference::where('store_id', $store->id)->where('id', $spId)->first();
        return response()->json([
            'status' => 'success',
            'shippingPreference' => $shippingPreference,
        ]);
    }
}
