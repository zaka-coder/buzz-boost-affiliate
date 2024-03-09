<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('buyer.products.index');
    }

    public function productsByCategory($category_id)
    {
        $category = Category::findOrFail($category_id);

        // get all products that are on auction and not expired or on buy-it-now
        $products = Product::where('category_id', $category_id)
            ->whereHas('productListing', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('sold', 0)
                        ->where('closed', 0);
                        // ->orWhere('item_type', 'buy-it-now');
                });
            })->get();

        return view('guest.products.prod-by-cat', compact('products', 'category'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        // extract store feedbacks
        $store = $product->store;
        $storeProducts = $store->products;
        $feedbacks = collect([]);
        foreach ($storeProducts as $storeProduct) {
            $feedbacks = $feedbacks->merge($storeProduct->feedbacks);
        }

        $related_products = null;
        // get 4 random related auction products
        $same_category_products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->whereHas('productListing', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('sold', 0)
                        ->where('closed', 0);
                        // ->orWhere('item_type', 'buy-it-now');
                });
            })
            ->limit(4)
            ->get();

        // dd($same_category_products);

        $related_products = $same_category_products;

        // if there is no related products or less than 4 products then get 4 products from the same store
        if ($same_category_products->count() < 4) {
            $same_store_products = Product::where('store_id', $product->store_id)
                ->where('id', '!=', $id)
                ->whereHas('productListing', function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('sold', 0)
                            ->where('closed', 0);
                            // ->orWhere('item_type', 'buy-it-now');
                    });
                })
                ->limit(4)
                ->get();

            // merge the two arrays
            $related_products = $same_category_products->merge($same_store_products);
        }
        // dd($related_products);

        return view('guest.products.product-detail', compact('product', 'related_products', 'feedbacks'));
    }

    public function requestAudit($id)
    {
        // get product with audit
        $product = Product::with('audit')->findOrFail($id);

        if ($product->audit == null) {
            // create audit
            Audit::create([
                'product_id' => $product->id,
                'user_id' => auth()->user()->id,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Request sent successfully',
            ]);
        } else {
            // return back
            return response()->json([
                'success' => false,
                'message' => 'Request already received. Please wait for the response',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
        ]);
    }
}
