<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('seller.settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:unique:users,email,' . $request->user()->id,
            'phone' => 'required'
        ]);

        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->profile()->update([
            'phone' => $request->phone
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
