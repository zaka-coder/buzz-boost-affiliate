<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $stores = Store::orderBy('id', 'desc')->get();
        return view('admin.stores.index', compact('stores'));
    }

    public function show($id)
    {
        $store = Store::findOrFail($id);
        return view('admin.stores.show', compact('store'));
    }

    public function approve($id)
    {
        $store = Store::findOrFail($id);
        // check if the store is approved then make it unapproved else make it approved
        if ($store->approved) {
            $store->approved = false;
            $store->save();

            // create notification
            $store->user->notify(new \App\Notifications\StoreApprovedNotification($store));

            Notification::create([
                'user_id' => $store->user_id,
                'title' => 'Your store has been rejected by admin',
                'is_read' => false
            ]);
        } else {
            $store->approved = true;
            $store->save();

            // create notification
            $store->user->notify(new \App\Notifications\StoreApprovedNotification($store));

            Notification::create([
                'user_id' => $store->user_id,
                'title' => 'Your store has been approved by admin',
                'is_read' => false
            ]);
        }
        // redirect back
        return redirect()->back()->with('success', 'Store status updated successfully');
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->status;
        $stores = Store::orderBy('id', 'desc')->where('approved', $status)->get();
        // dd($stores);
        return view('admin.stores.index', compact('stores', 'status'));
    }
}
