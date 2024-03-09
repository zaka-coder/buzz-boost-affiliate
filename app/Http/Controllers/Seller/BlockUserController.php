<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlockUserController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store; // get current store

        // get blocked users for the current store from blocked_users table
        $blocked = DB::table('blocked_users')
            ->where('store_id', $store->id)
            ->get();

        // get blocked users data from users table
        $blocked_users = User::whereIn('id', $blocked->pluck('user_id'))->get();

        // extract created_at from blocked_users table and add to $blocked_users
        foreach ($blocked_users as $user) {
            // get created_at from blocked_users table
            $blockedAt = $blocked->where('user_id', $user->id)->first()->created_at;
            // format date to d M Y
            $user->blocked_at = Carbon::parse($blockedAt)->format('d M Y');
        }

        return view('seller.sales.block-bidders', compact('blocked_users'));
    }

    public function block($id)
    {
        $user = User::findOrFail($id);
        $store = Auth::user()->store; // get current store

        // add user->id and store->id to blocked_users table using raw query
        $blocked = DB::table('blocked_users')->insert([
            'user_id' => $user->id,
            'store_id' => $store->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if($blocked) {
            return back()->with('success', 'User blocked successfully');
        }

        return back()->with('error', 'Something went wrong');

    }
    public function unblock($id)
    {
        $user = User::findOrFail($id);
        $store = Auth::user()->store; // get current store

        // delete row from blocked_users table where user->id and store->id match with current user and store id using raw query
        $unblocked = DB::table('blocked_users')->where('user_id', $user->id)->where('store_id', $store->id)->delete();

        if($unblocked) {
            return back()->with('success', 'User unblocked successfully');
        }

        return back()->with('error', 'Something went wrong');

    }
}
