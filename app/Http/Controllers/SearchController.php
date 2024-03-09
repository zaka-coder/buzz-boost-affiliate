<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index($search = null)
    {
        $search = trim($search);
        // dd($search);
        if ($search != null && $search != '') {
            $products = Product::whereHas('productListing', function ($query) {
                $query->where('sold', 0);
            })->where('is_sold', 0)
                ->where('name', 'LIKE', '%' . $search . '%')
                ->get();

            $categories = Category::where('name', 'LIKE', '%' . $search . '%')->get();

            $stores = Store::where('name', 'LIKE', '%' . $search . '%')->where('approved', 1)->get();
            return view('guest.search', compact('products', 'categories', 'stores', 'search'));
        } else {
            return redirect()->back();
        }
    }
}
