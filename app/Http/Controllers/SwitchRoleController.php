<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwitchRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // check for active role
        if ($user->hasRole('seller')) {
            // Assign buyer role
            $user->assignRole('buyer');
            // Remove seller role
            $user->removeRole('seller');
            // Redirect to dashboard
            return redirect()->route('dashboard');

        } elseif ($user->hasRole('buyer')) {
            if($user->profile == null)
            {
                return redirect()->route('buyer.profile.create');
            }
            // Assign seller role
            $user->assignRole('seller');
            // Remove buyer role
            $user->removeRole('buyer');
            // Redirect to dashboard
            return redirect()->route('dashboard');

        } else {
            // Redirect to dashboard
            return redirect()->route('dashboard');
        }
    }
}
