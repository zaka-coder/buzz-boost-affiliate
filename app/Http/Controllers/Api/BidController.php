<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function index()
    {
        $bids = Bid::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'bids' => $bids
        ]);
    }
}
