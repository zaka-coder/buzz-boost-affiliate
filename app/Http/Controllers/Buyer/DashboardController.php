<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyer']);
    }

    public function index()
    {
        if(auth()->user()->profile){
            return view('buyer.dashboard');
        } else {
            return redirect()->route('buyer.profile.create');
        }
    }
}
