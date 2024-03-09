<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Traits\RatingTrait;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use RatingTrait;

    public function index($search = null)
    {
        $stores = null;
        // filter by search
        if ($search) {
            $stores = Store::where('approved', 1)->where('name', 'like', '%' . $search . '%')->get();
        } else {
            $stores = Store::where('approved', 1)->get();
        }
        return view('guest.stores.index', compact('stores', 'search'));
    }

    public function show($id)
    {
        $store = Store::findOrFail($id);
        $products = Product::whereHas('productListing', function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('sold', 0)
                    ->where('closed', 0);
                    // ->orWhere('item_type', 'buy-it-now');
            });
        })->where('store_id', $id)
        ->where('is_sold', 0)
        ->get();
        return view('guest.stores.show', compact('store', 'products'));
    }
}
