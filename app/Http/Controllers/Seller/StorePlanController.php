<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\CardException;
use Stripe\StripeClient;
use Exception;

class StorePlanController extends Controller
{
    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $store = Store::findOrFail($id);
        // dd($plan);
        try {
            if ($plan->name == 'Starter') {
                $store->plan_id = $plan->id;
                $store->save();
                return redirect()->back()->with('success', 'Your plan has been updated successfully');
            } else {
                // charge the user the price of the selected plan
                $stripe = new StripeClient(getenv('STRIPE_SECRET'));

                $response = $stripe->paymentIntents->create([
                    'payment_method_types' => ['card'],
                    'amount' => 100 * $plan->price,
                    'currency' => 'usd',
                    'payment_method' => $request->payment_method_id,
                    'description' => 'Payment for store plan selection',
                    'confirm' => true,
                ]);

                // dd($response);
                if ($response->status == 'succeeded') {
                    $store->update([
                        'plan_id' => $plan->id
                    ]);

                    return redirect()->back()->with('success', 'Your plan has been updated successfully');
                } else {
                    throw new Exception("There was a problem processing your payment", 1);
                }
            }
        } catch (CardException $th) {
            throw new Exception("There was a problem processing your payment", 1);
        }
    }
}
