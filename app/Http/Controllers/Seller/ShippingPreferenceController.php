<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ShippingPreference;
use App\Models\ShippingProvider;
use App\Models\Store;
use Illuminate\Http\Request;

class ShippingPreferenceController extends Controller
{
    //

    public function store(Request $request)
    {
        // validate the request
        // $request->validate([

        // ]);

        try {

            $shippingPreference = new ShippingPreference();
            $shippingPreference->user_id = auth()->user()->id;
            $shippingPreference->shipping_terms = $request->shipping_terms;
            $shippingPreference->insurance = $request->insurance;
            $shippingPreference->shipping_provider_id = $request->shipping_provider;
            $shippingPreference->domestic_shipping_fee_per_item = $request->domestic_shipping_per_item;
            $shippingPreference->domestic_transit_time = $request->domestic_transit_time;
            $shippingPreference->combine_shipping = $request->combine_shipping;
            $shippingPreference->domestic_bulk_discount_rate = $request->domestic_bulk_discount_rate;
            $shippingPreference->minimum_order_quantity = $request->minimum_order_quantity;
            $shippingPreference->save();

            return response()->json([
                'message' => __('Shipping preference added successfully!'),
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ]);
        }
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        // $shipping = $store->shipping;
        // dd($store);
        if (!$store->approved) {
            return view('seller.store.not_approved');
        }
        return view('seller.store.shipping', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        // check if fedex shipping provider is selected
        if ($request->has('fedex')) {
            // search for fedex like name in shipping providers
            $shippingProvider = ShippingProvider::where('name', 'LIKE', '%fedex%')->first();

            // validate the request
            $request->validate([
                'fedex_shipping_per_item' => 'required',
                'fedex_transit_time' => 'required',
            ]);

            // check if combine shipping is selected
            if($request->fedex_combine == 'yes') {
                // validate the request
                $request->validate([
                    'fedex_bulk_discount' => 'required',
                    'fedex_min_quantity' => 'required',
                ]);
            }

            // update or create Fedex shipping for store
            ShippingPreference::updateOrCreate([
                'store_id' => $store->id,
                'shipping_provider_id' => $shippingProvider->id
            ], [
                'domestic_shipping_fee_per_item' => $request->fedex_shipping_per_item,
                'domestic_transit_time' => $request->fedex_transit_time,
                'combine_shipping' => $request->fedex_combine,
                'domestic_bulk_discount_rate' => $request->fedex_bulk_discount,
                'minimum_order_quantity' => $request->fedex_min_quantity,
                'shipping_provider' => $request->fedex,
                'shipping_terms' => $store->shipping_terms,
                'insurance' => $store->insurance,
            ]);
        } else {
            // delete Fedex shipping for store
            $shipping = ShippingPreference::where('store_id', $store->id)->where('shipping_provider', 'fedex')->first();
            if ($shipping) {
                $shipping->delete();
            }
        }

        // check if dhl shipping provider is selected
        if ($request->has('dhl')) {
            // search for dhl like name in shipping providers
            $shippingProvider = ShippingProvider::where('name', 'LIKE', '%dhl%')->first();

            // validate the request
            $request->validate([
                'dhl_shipping_per_item' => 'required',
                'dhl_transit_time' => 'required',
            ]);

            // check if combine shipping is selected
            if($request->dhl_combine == 'yes') {
                // validate the request
                $request->validate([
                    'dhl_bulk_discount' => 'required',
                    'dhl_min_quantity' => 'required',
                ]);
            }

            // update or create dhl shipping for store
            ShippingPreference::updateOrCreate([
                'store_id' => $store->id,
                'shipping_provider_id' => $shippingProvider->id
            ], [
                'domestic_shipping_fee_per_item' => $request->dhl_shipping_per_item,
                'domestic_transit_time' => $request->dhl_transit_time,
                'combine_shipping' => $request->dhl_combine,
                'domestic_bulk_discount_rate' => $request->dhl_bulk_discount,
                'minimum_order_quantity' => $request->dhl_min_quantity,
                'shipping_provider' => $request->dhl,
                'shipping_terms' => $store->shipping_terms,
                'insurance' => $store->insurance,
            ]);
        } else {
            // delete Fedex shipping for store
            $shipping = ShippingPreference::where('store_id', $store->id)->where('shipping_provider', 'dhl')->first();
            if ($shipping) {
                $shipping->delete();
            }
        }

        // check if express shipping provider is selected
        if ($request->has('express')) {
            // search for express like name in shipping providers
            $shippingProvider = ShippingProvider::where('name', 'LIKE', '%express%')->first();

            // validate the request
            $request->validate([
                'express_shipping_per_item' => 'required',
                'express_transit_time' => 'required',
            ]);

            // check if combine shipping is selected
            if($request->express_combine == 'yes') {
                // validate the request
                $request->validate([
                    'express_bulk_discount' => 'required',
                    'express_min_quantity' => 'required',
                ]);
            }

            // update or create express shipping for store
            ShippingPreference::updateOrCreate([
                'store_id' => $store->id,
                'shipping_provider_id' => $shippingProvider->id
            ], [
                'domestic_shipping_fee_per_item' => $request->express_shipping_per_item,
                'domestic_transit_time' => $request->express_transit_time,
                'combine_shipping' => $request->express_combine,
                'domestic_bulk_discount_rate' => $request->express_bulk_discount,
                'minimum_order_quantity' => $request->express_min_quantity,
                'shipping_provider' => $request->express,
                'shipping_terms' => $store->shipping_terms,
                'insurance' => $store->insurance,
            ]);
        } else {
            // delete Fedex shipping for store
            $shipping = ShippingPreference::where('store_id', $store->id)->where('shipping_provider', 'express')->first();
            if ($shipping) {
                $shipping->delete();
            }
        }

        // check if registered shipping provider is selected
        if ($request->has('registered')) {
            // search for registered like name in shipping providers
            $shippingProvider = ShippingProvider::where('name', 'LIKE', '%registered%')->first();

            // validate the request
            $request->validate([
                'reg_shipping_per_item' => 'required',
                'reg_transit_time' => 'required',
            ]);

            // check if combine shipping is selected
            if($request->reg_combine == 'yes') {
                // validate the request
                $request->validate([
                    'reg_bulk_discount' => 'required',
                    'reg_min_quantity' => 'required',
                ]);
            }

            // update or create registered shipping for store
            ShippingPreference::updateOrCreate([
                'store_id' => $store->id,
                'shipping_provider_id' => $shippingProvider->id
            ], [
                'domestic_shipping_fee_per_item' => $request->reg_shipping_per_item,
                'domestic_transit_time' => $request->reg_transit_time,
                'combine_shipping' => $request->reg_combine,
                'domestic_bulk_discount_rate' => $request->reg_bulk_discount,
                'minimum_order_quantity' => $request->reg_min_quantity,
                'shipping_provider' => $request->registered,
                'shipping_terms' => $store->shipping_terms,
                'insurance' => $store->insurance,
            ]);
        } else {
            // delete Fedex shipping for store
            $shipping = ShippingPreference::where('store_id', $store->id)->where('shipping_provider', 'registered')->first();
            if ($shipping) {
                $shipping->delete();
            }
        }

        // check if standard shipping provider is selected
        if ($request->has('standard')) {
            // search for standard like name in shipping providers
            $shippingProvider = ShippingProvider::where('name', 'LIKE', '%standard%')->first();

            // validate the request
            $request->validate([
                'standard_shipping_per_item' => 'required',
                'standard_transit_time' => 'required',
            ]);

            // update or create standard shipping for store
            ShippingPreference::updateOrCreate([
                'store_id' => $store->id,
                'shipping_provider_id' => $shippingProvider->id
            ], [
                'domestic_shipping_fee_per_item' => $request->standard_shipping_per_item,
                'domestic_transit_time' => $request->standard_transit_time,
                'shipping_provider' => $request->standard,
                'shipping_terms' => $store->shipping_terms,
                'insurance' => $store->insurance,
            ]);
        } else {
            // delete Fedex shipping for store
            $shipping = ShippingPreference::where('store_id', $store->id)->where('shipping_provider', 'standard')->first();
            if ($shipping) {
                $shipping->delete();
            }
        }

        return redirect()->back()->with('success', 'Shipping details updated successfully!');
    }
}
