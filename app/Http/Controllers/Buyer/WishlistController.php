<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $items = $user->wishlist;

        // dd($items);
        return view('buyer.wishlist.wishlist', compact('items'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->profile){
            return redirect()->back()->with('error', 'Please complete your profile first.');
        }
        // dd($request->all());
        $user = auth()->user();
        // check if item is already in wishlist
        if ($user->wishlist()->where('product_id', $request->product_id)->exists()) {
            return redirect()->back()->with('info', 'Item already in wishlist');
        }

        // add item to wishlist table using raw query
        $wishlist = DB::table('wishlists')->insert([
            'product_id' => $request->product_id,
            'user_id' => $user->id
        ]);

        return redirect()->back()->with('success', 'Item added to wishlist successfully');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $user->wishlist()->detach($id);
        return redirect()->back()->with('success', 'Item removed from wishlist successfully');
    }
}
