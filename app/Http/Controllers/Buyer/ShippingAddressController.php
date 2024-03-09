<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\ProductShipping;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    public function index()
    {

    }

    public function create($id)
    {
        return view('buyer.shipping.edit', compact('id'));
    }

    public function store(Request $request, $id)
    {
       $validated = $request->validate([
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            // 'phone' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        $shipping = ShippingAddress::create([
            'product_id' => $id,
            'user_id' => auth()->user()->id,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            // 'phone' => $request->phone,
            'customer_name' => $request->name,
            'email' => $request->email
        ]);
        if($shipping){
            return redirect()->route('buyer.checkout', $id)->with('success', 'Shipping address added successfully');
        }

        return redirect()->back()->with('error', 'Something went wrong');

    }
}
