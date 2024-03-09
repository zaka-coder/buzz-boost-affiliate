<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('seller.payment-method.index');
    }

    public function store(Request $request)
    {
        if($request->tax == 'yes'){
            $request->validate([
                'tax_rate' => 'required|numeric|min:1|max:100',
            ]);
        }

        $taxRate = $request->tax_rate;
        if($request->tax_rate == null){
            $taxRate = 0;
        }

        try {

            $paymentMethod = new PaymentMethod();
            $paymentMethod->user_id = auth()->user()->id;
            $paymentMethod->name = $request->name;
            $paymentMethod->tax_rate = $taxRate;

            $paymentMethod->save();

            return response()->json([
                'message' => __('Payment method added successfully!'),
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ]);
        }
    }
}
