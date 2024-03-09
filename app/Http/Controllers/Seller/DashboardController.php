<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'seller']);
    }

    public function index()
    {
        // Check if the user has a store
        if (auth()->user()->store) {
            // Check if the store is active
            if (auth()->user()->store->approved == 1) {
                $store = auth()->user()->store;
                $products = $store->products; // get all store products
                $offers = $products->pluck('offers')->flatten(); // get all offers from all products

                // filter products whereHas productListing and in productListing reserved is 0
                $no_reserve = $store->products()->whereHas('productListing', function ($query) {
                    $query->where('reserved', 0)->where('item_type', 'auction');
                })->get();

                $boosted = $store->products()->whereHas('productListing', function ($query) {
                    $query->where('listing_type', 'Boost');
                })->get();

                $premium = $store->products()->whereHas('productListing', function ($query) {
                    $query->where('listing_type', 'Premium');
                })->get();

                // filter products from store whereHas productListing and in productListing item_type is auction and closed is 0
                $open_auctions = $store->products()->whereHas('productListing', function ($query) {
                    $query->where('item_type', 'auction')->where('closed', 0);
                })->where('is_sold', 0)
                    ->get();
                // dd($open_auctions->count());

                $closed_auctions = $store->products()->whereHas('productListing', function ($query) {
                    $query->where('item_type', 'auction')->where('closed', 1);
                })
                    ->get();
                // dd($closed_auctions->count());
                $buyitnow_items = $store->products()->whereHas('productListing', function ($query) {
                    $query->where('item_type', 'buy-it-now');
                })
                    ->get();

                return view('seller.dashboard', compact('store', 'open_auctions', 'closed_auctions', 'buyitnow_items', 'offers', 'no_reserve', 'boosted', 'premium'));
            } else {
                return redirect()->route('seller.store.wait-to-be-approved');
            }
        }
        return redirect()->route('seller.store.create');
    }
}
