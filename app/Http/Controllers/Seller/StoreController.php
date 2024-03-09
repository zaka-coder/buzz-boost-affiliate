<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\ShippingPreference;
use App\Models\ShippingProvider;
use App\Models\Store;
use App\Models\Plan;
use App\Models\StoreEmailNotification;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        // dd(auth()->user()->roles);
        if(auth()->user()->profile == null){
            return redirect()->route('buyer.profile.create');
        }
        if(auth()->user()->store != null){
            return redirect()->route('seller.dashboard');
        }
        return view('seller.store.create');
    }

    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'registered' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->combine_shipping == 'yes') {
            $request->validate([
                'domestic_bulk_discount_rate' => 'required',
                'minimum_order_quantity' => 'required',
            ]);
        }

        // if ($request->tax == 'yes') {
        //     $request->validate([
        //         'tax_rate' => 'required|numeric|min:1|max:100',
        //     ]);
        // }


        // try {

        $image_path = null;
        // check if there is an image
        if ($request->hasFile('image')) {
            // $request->validate([
            //     'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // ]);

            $imageName = time() . '.' . $request->image->extension();
            // store image
            $request->image->move(public_path('images/store'), $imageName);
            $image_path = 'images/store/' . $imageName;
        }

        $plan = Plan::where('name', 'Starter')->first();

        // create store
        $store = new Store();
        $store->user_id = auth()->user()->id;
        $store->name = $request->input('name');
        $store->email = $request->input('email');
        $store->phone = $request->input('phone');
        $store->address = $request->input('address');
        $store->country = $request->input('country');
        $store->city = $request->input('city');
        $store->state = $request->input('state');
        $store->registered = $request->input('registered');
        $store->website = $request->input('website');
        $store->image = $image_path;
        $store->approved = false;
        $store->shipping_terms = $request->shipping_terms;
        $store->insurance = $request->insurance;
        $store->plan_id = $plan->id ?? null;
        $store->save();

        // get shipping provider name
        $shippingProviderName = '';
        $shippingProvider = ShippingProvider::find($request->shipping_provider);
        // dd($shippingProvider);
        if ($shippingProvider) {
            $shippingProviderName = $shippingProvider->name;
            // check if shipping provider name is like fedex something then asign fedex to shippingProviderName
            if (strpos(strtolower($shippingProviderName), 'fedex') !== false) {
                $shippingProviderName = 'fedex';
            } elseif (strpos(strtolower($shippingProviderName), 'dhl') !== false) {
                $shippingProviderName = 'dhl';
            } elseif (strpos(strtolower($shippingProviderName), 'express') !== false) {
                $shippingProviderName = 'express';
            } elseif (strpos(strtolower($shippingProviderName), 'registered') !== false) {
                $shippingProviderName = 'registered';
            } elseif (strpos(strtolower($shippingProviderName), 'standard') !== false) {
                $shippingProviderName = 'standard';
            }
        }

        // create shipping preference
        $shippingPreference = new ShippingPreference();
        $shippingPreference->store_id = $store->id;
        $shippingPreference->shipping_terms = $request->shipping_terms;
        $shippingPreference->insurance = $request->insurance;
        $shippingPreference->shipping_provider_id = $request->shipping_provider;
        $shippingPreference->shipping_provider = $shippingProviderName;
        $shippingPreference->domestic_shipping_fee_per_item = $request->domestic_shipping_per_item;
        $shippingPreference->domestic_transit_time = $request->domestic_transit_time;
        $shippingPreference->combine_shipping = $request->combine_shipping;
        $shippingPreference->domestic_bulk_discount_rate = $request->domestic_bulk_discount_rate;
        $shippingPreference->minimum_order_quantity = $request->minimum_order_quantity;
        $shippingPreference->save();

        // create store email notifications
        StoreEmailNotification::create([
            'store_id' => $store->id,
            'offer_made' => true,
            'item_sold' => true,
            'payment_received' => true,
        ]);

        return redirect()->route('seller.store.thanks')->with('success', 'Store created successfully');
        // } catch (\Throwable $th) {
        //     return redirect()->back()->with('error', 'Something went wrong, please try again!');
        // }
    }

    public function thanks()
    {
        if (auth()->user()->store == null) {
            return redirect()->route('seller.store.create');
        } elseif (auth()->user()->store->approved) {
            return redirect()->route('seller.dashboard');
        }
        return view('seller.store.thanks');
    }

    public function waitToBeApproved()
    {
        if (auth()->user()->store == null) {
            return redirect()->route('seller.store.create');
        } elseif (auth()->user()->store->approved) {
            return redirect()->route('seller.dashboard');
        }
        return view('seller.store.not_approved');
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        // dd($store);
        if (!$store->approved) {
            return view('seller.store.not_approved');
        }
        return view('seller.store.store_details', compact('store'));
    }

    public function update(Request $request, $id)
    {

        // validate the request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'registered' => 'required',
        ]);

        // update store
        $store = Store::findOrFail($id);
        $store->name = $request->input('name');
        $store->email = $request->input('email');
        $store->phone = $request->input('phone');
        $store->address = $request->input('address');
        $store->country = $request->input('country');
        $store->city = $request->input('city');
        $store->state = $request->input('state');
        $store->registered = $request->input('registered');
        $store->website = $request->input('website');
        $store->insurance = $request->input('insurance');
        $store->shipping_terms = $request->input('shipping_terms');
        $store->description = $request->input('description');
        $store->tax = $request->input('tax');
        $store->minimum_offer = $request->input('minimum_offer');
        $store->save();

        return redirect()->back()->with('success', 'Store updated successfully');
    }

    public function comparePlans($id)
    {
        $store = Store::findOrFail($id);
        // dd($store);
        if (!$store->approved) {
            return view('seller.store.not_approved');
        }
        $plans = Plan::all();
        return view('seller.store.compare-plans', compact('store', 'plans'));
    }

    public function updateStoreImage(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        if ($request->hasFile('store_image')) {
            // check for old image
            if ($store->image) {
                // delete old image from public folder
                unlink(public_path($store->image));
            }

            $request->validate([
                'store_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $image = $request->file('store_image');


            $imageName = time() . '.' . $image->extension();
            // store image
            $image->move(public_path('images/store'), $imageName);
            $image_path = 'images/store/' . $imageName;

            $store->image = $image_path;
            $store->save();

            return redirect()->back()->with('success', 'Store image updated successfully');
        }

        return redirect()->back()->with('error', 'Something went wrong, please try again!');

    }
}
