<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    //

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        // dd($store);
        if (!$store->approved) {
            return view('seller.store.not_approved');
        }
        return view('seller.store.discounts', compact('store'));
    }
}
