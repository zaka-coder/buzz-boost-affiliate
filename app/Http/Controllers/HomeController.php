<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // get all products that are not closed or sold
        $products = Product::whereHas('productListing', function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('sold', 0)
                    ->where('closed', 0);
            });
        })->where('is_sold', 0)
            ->get();

        // get 4 random showcase products
        $showcaseProducts = Product::whereHas('productListing', function ($query) {
            $query->where('listing_type', 'showcase')
                ->where('item_type', 'buy-it-now')
                ->where('sold', 0)
                ->where('closed', 0);
        })->where('is_sold', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // get 4 random premium products
        $premiumProducts = Product::whereHas('productListing', function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('sold', 0)
                    ->where('closed', 0)
                    ->where('listing_type', 'premium');
            });
        })->where('is_sold', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('welcome', compact('showcaseProducts', 'premiumProducts', 'products'));
    }

    // public function index()
    // {
    //     // get all products that are on auction and not expired or on buy-it-now
    //     $products = Product::whereHas('productListing', function ($query) {
    //         $query->where(function ($subQuery) {
    //             $subQuery->where('start_time', '<=', date('Y-m-d H:i:s'))
    //                 ->where('end_time', '>=', date('Y-m-d H:i:s'))
    //                 ->orWhere('item_type', 'buy-it-now');
    //         });
    //     })->where('is_sold', 0)
    //         ->get();

    //     // get 4 random showcase products
    //     $showcaseProducts = Product::whereHas('productListing', function ($query) {
    //         $query->where('listing_type', 'showcase')
    //             ->where('item_type', 'buy-it-now');
    //     })->where('is_sold', 0)
    //         ->inRandomOrder()
    //         ->limit(4)
    //         ->get();

    //     // get 4 random premium products
    //     $premiumProducts = Product::whereHas('productListing', function ($query) {
    //         $query->where(function ($subQuery) {
    //             $subQuery->where('start_time', '<=', date('Y-m-d H:i:s'))
    //                 ->where('end_time', '>=', date('Y-m-d H:i:s'))
    //                 ->orWhere('item_type', 'buy-it-now')
    //                 ->where('listing_type', 'premium');
    //         });
    //     })->where('is_sold', 0)
    //         ->inRandomOrder()
    //         ->limit(4)
    //         ->get();

    //     return view('welcome', compact('showcaseProducts', 'premiumProducts', 'products'));
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // check for logged in user
        if (!Auth::check()) {
            return redirect('/login');
        }

        // check user role and redirect to appropriate dashboard
        $user = Auth::user();
        if ($user->hasRole('buyer')) {
            if (!$user->profile) {
                return redirect()->route('buyer.profile.create');
            }
            return redirect()->route('buyer.dashboard');
        } elseif ($user->hasRole('seller')) {
            return redirect()->route('seller.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
    }
}
