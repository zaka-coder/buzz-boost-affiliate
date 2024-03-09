<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function updateStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        Notification::create([
            'user_id' => $product->store->user_id,
            'title' => 'The status of your item named: ' . $product->name . ' changed to ' . $product->status . ' by Admin',
            'is_read' => 0,
        ]);

        return redirect()->back()->with('success', 'Product status updated successfully.');
    }

    public function filterByItemId(Request $request)
    {
        $itemId = $request->itemId;
        // check if itemId is like GM123, remove the GM or other alphabets
        $id = preg_replace('/[A-Za-z]+/', '', $itemId);
        $products = Product::where('id', $id)->get();
        return view('admin.products.index', compact('products', 'itemId'));
    }
    public function filterByItemName(Request $request)
    {
        $itemName = $request->itemName;
        $products = Product::where('name', 'like', '%' . $itemName . '%')->get();
        return view('admin.products.index', compact('products', 'itemName'));
        // return response()->json($orders);
    }

    public function filterByStoreName(Request $request)
    {
        $storeName = $request->storeName;
        // dd($storeName);
        $products = Product::whereHas('store', function ($q) use ($storeName) {
            $q->where('name', 'like', '%' . $storeName . '%');
        })->get();

        return view('admin.products.index', compact('products', 'storeName'));
    }

    public function filterByDate(Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $endDate = date('Y-m-d', strtotime($endDate . ' +1 day')); // add 1 day to endDate to include the end day orders

        $products = Product::whereBetween('created_at', [$startDate, $endDate])->get();
        // dd($orders);

        return view('admin.products.index', compact('products'));
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->status;
        // dd($customerName);
        $products = Product::where('status', 'like', '%' . $status . '%')->get();

        return view('admin.products.index', compact('products', 'status'));
    }

    public function filterBySaleType(Request $request)
    {
        $saleType = $request->saleType;
        // dd($customerName);
        $products = Product::whereHas('productListing', function ($q) use ($saleType) {
            $q->where('item_type', 'like', '%' . $saleType . '%');
        })->get();
        // dd($products);

        return view('admin.products.index', compact('products', 'saleType'));
    }
}
