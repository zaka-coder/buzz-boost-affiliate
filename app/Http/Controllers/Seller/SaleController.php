<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Traits\RatingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    use RatingTrait;
    public function index($status = null)
    {
        $user = auth()->user();
        $store = $user->store;

        if($status == null) {
            $status = 'pending';
        }

        $orders = Order::with('orderItems')->where('store_id', $store->id)->where('status', $status)->get();

        // // filter orders by status
        // if ($status) {
        //     $orders = $orders->where('status', $status);
        // } else {

        // }

        $orders = $orders->groupBy('user_id');
        // dd($orders);
        return view('seller.sales.index', compact('orders', 'status'));
    }

    public function updateStatus($id, $status)
    {
        $order = Order::findOrFail($id);
        $order->status = $status;
        $order->save();

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order status updated to ' . $status,
            'is_read' => 0
        ]);

        return redirect()->back()->with('success', 'Status updated successfully');
    }
    public function updateStatusShipped(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'tracking_number' => 'required',
            'status' => 'required',
        ]);
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->tracking_number = $request->tracking_number;
        $order->ship_date = now()->format('Y-m-d');
        $order->save();

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Order status updated to ' . $request->status,
            'is_read' => 0,
        ]);

        $order->user->notify(new \App\Notifications\OrderShippedNotification($order));

        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function customerProfile($id)
    {
        $customer = User::findOrFail($id);

        $feedbacks = Feedback::where('user_id', $customer->id)->orderBy('id', 'desc')->get();

        // count positive and negative feedbacks
        $positive = $feedbacks->where('nature', 'positive')->count();
        $negative = $feedbacks->where('nature', 'negative')->count();
        $neutral = $feedbacks->where('nature', 'neutral')->count();

        $ratings = null;
        if (!$feedbacks->isEmpty()) {
            $ratings = $this->ratingPercentage($feedbacks); // rating percentage from RatingTrait
        }

        $store = Auth::user()->store;
        // check if customer is blocked by current store
        $isBlocked = $store->blocked_users->contains($customer->id);

        if ($isBlocked) {
            // Customer is blocked by the current store
            $customer->is_blocked = true;
        } else {
            // Customer is not blocked by the current store
            $customer->is_blocked = false;
        }

        return view('seller.sales.profile', compact(
            'customer',
            'feedbacks',
            'positive',
            'negative',
            'neutral',
            'ratings'
        ));
    }

    public function summary($id)
    {
        $order = Order::findOrFail($id);
        $user = $order->user;

        return view('seller.sales.sales_summary', compact('order', 'user'));
    }

}
