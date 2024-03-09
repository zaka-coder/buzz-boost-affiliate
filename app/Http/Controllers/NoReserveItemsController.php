<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class NoReserveItemsController extends Controller
{
    public function index()
    {
        // get all products that are on auction and not expired or on buy-it-now
        $products = Product::whereHas('productListing', function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('item_type', 'auction')
                    ->where('reserved', 1)
                    ->where('closed', 0)
                    ->where('sold', 0);
            });
        })->where('is_sold', 0)
            ->get();

        return view('guest.products.no-reserve', compact('products'));
    }
}
