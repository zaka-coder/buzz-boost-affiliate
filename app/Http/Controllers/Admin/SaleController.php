<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('admin.sales.index', compact('orders'));
    }

    public function filterByItemId(Request $request)
    {
        $itemId = $request->itemId;
        // check if itemId is like GM123, remove the GM or other alphabets
        $id = preg_replace('/[A-Za-z]+/', '', $itemId);
        // dd($id);
        $orders = Order::whereHas('products', function ($q) use ($id) {
            $q->where('products.id', $id);
        })->get();
        return view('admin.sales.index', compact('orders', 'itemId'));
    }
    public function filterByItemName(Request $request)
    {
        $itemName = $request->itemName;
        $orders = Order::whereHas('products', function ($q) use ($itemName) {
            $q->where('name', 'like', '%' . $itemName . '%');
        })->get();
        return view('admin.sales.index', compact('orders', 'itemName'));
        // return response()->json($orders);
    }

    public function filterByStoreName(Request $request)
    {
        $storeName = $request->storeName;
        // dd($storeName);
        $orders = Order::whereHas('store', function ($q) use ($storeName) {
            $q->where('name', 'like', '%' . $storeName . '%');
        })->get();

        return view('admin.sales.index', compact('orders', 'storeName'));
    }

    public function filterByCustomerName(Request $request)
    {
        $customerName = $request->customerName;
        // dd($customerName);
        $orders = Order::whereHas('user', function ($q) use ($customerName) {
            $q->where('name', 'like', '%' . $customerName . '%');
        })->get();

        return view('admin.sales.index', compact('orders', 'customerName'));
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

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
        // dd($orders);

        return view('admin.sales.index', compact('orders'));
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->status;
        // dd($customerName);
        $orders = Order::where('status', 'like', '%' . $status . '%')->get();

        return view('admin.sales.index', compact('orders', 'status'));
    }
}
