<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class EmailNotificationsSetupController extends Controller
{
    //

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        // dd($store);
        if (!$store->approved) {
            return view('seller.store.not_approved');
        }
        return view('seller.store.email-notifications', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $inputs = [];
        $inputs['store_id'] = $store->id;


        // check if offer_made checkbox is checked
        if (!$request->has('offer_made')) {
            $inputs['offer_made'] = 0;
        } else {
            $inputs['offer_made'] = 1;
        }

        // check if item_sold checkbox is checked
        if (!$request->has('item_sold')) {
            $inputs['item_sold'] = 0;
        } else {
            $inputs['item_sold'] = 1;
        }

        // check if payment_received checkbox is checked
        if (!$request->has('payment_received')) {
            $inputs['payment_received'] = 0;
        } else {
            $inputs['payment_received'] = 1;
        }
        
        // check if store has setup email notifications
        if (!$store->emailNotifications) {
            $store->emailNotifications()->create($inputs);
        } else {
            $store->emailNotifications->update($inputs);
        }
        return redirect()->back()->with('success', 'Email notifications setup successfully.');
    }
}
