<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StorePaymentMethod;
use Illuminate\Http\Request;

class StorePaymentMethodController extends Controller
{
    //

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        // dd($paymentMethods);
        if (!$store->approved) {
            return view('seller.store.not_approved');
        }
        $paymentMethods = $store->paymentMethods;
        return view('seller.store.payment', compact('store', 'paymentMethods'));
    }

    public function update(Request $request, $id)
    {
        try {
            $store = Store::findOrFail($id);
            // dd($request->all());

            // check if payment method exists
            if (isset($request->paypal)) {

                // check if payment method exists
                $paymentMethod = StorePaymentMethod::where('store_id', $store->id)
                    ->where('name', $request->paypal)->first();
                // update or create
                if ($paymentMethod) {
                    // update payment method
                    $paymentMethod->update([
                        // update the fields you want to change
                        'email' => $request->email,
                        'status' => true,
                    ]);
                } else {
                    $request->validate([
                        'email' => 'nullable|email',
                        'paypal_key' => 'required|string|min:25',
                        'paypal_secret' => 'required|string|min:25',
                    ]);

                    // create new payment method
                    StorePaymentMethod::create([
                        'store_id' => $store->id,
                        'name' => $request->paypal,
                        'email' => $request->email,
                        'key' => $request->paypal_key,
                        'secret' => encrypt($request->paypal_secret),
                        'status' => true,
                    ]);
                }
            } else {
                // check if payment method exists
                $paymentMethod = StorePaymentMethod::where('store_id', $store->id)
                    ->where('name', 'paypal')->first();
                // update or create
                if ($paymentMethod) {
                    // update payment method
                    $paymentMethod->update([
                        // update the fields you want to change
                        'status' => false,
                    ]);
                }
            }

            // check if payment method exists
            if (isset($request->stripe)) {
                // check if payment method exists
                $paymentMethod = StorePaymentMethod::where('store_id', $store->id)
                    ->where('name', $request->stripe)->first();
                // update or create
                if ($paymentMethod) {
                    // update payment method
                    $paymentMethod->update([
                        // update the fields you want to change
                        'status' => true,
                    ]);
                } else {
                    $request->validate([
                        'stripe_key' => 'required|string|min:25',
                        'stripe_secret' => 'required|string|min:25',
                    ]);

                    // create new payment method
                    StorePaymentMethod::create([
                        'store_id' => $store->id,
                        'name' => $request->stripe,
                        'key' => $request->stripe_key,
                        'secret' => encrypt($request->stripe_secret),
                        'status' => true,
                    ]);
                }
            } else {
                // check if payment method exists
                $paymentMethod = StorePaymentMethod::where('store_id', $store->id)
                    ->where('name', 'stripe')->first();
                // update or create
                if ($paymentMethod) {
                    // update payment method
                    $paymentMethod->update([
                        // update the fields you want to change
                        'status' => false,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Payment method updated successfully');
        } catch (\Throwable $th) {
            // return redirect()->back()->with('error', $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function reset(Request $request, $id)
    {
        try {
            $store = Store::findOrFail($id);
            $method = $request->method;
            $paymentMethod = StorePaymentMethod::where('store_id', $store->id)
                ->where('name', $method)->first();
            if ($paymentMethod) {
                // dd($paymentMethod);
                $paymentMethod->delete();
            }
            return redirect()->back()->with('success', 'Payment method reset successfully');
        } catch (\Throwable $th) {
            // return redirect()->back()->with('error', $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
