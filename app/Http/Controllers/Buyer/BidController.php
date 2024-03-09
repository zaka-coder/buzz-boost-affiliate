<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Notification;
use App\Notifications\BidNotification;
use App\Notifications\OutBidNotification;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function index($status = null)
    {
        $bids = Bid::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
        // filter offers by status
        if ($status) {
            $bids = $bids->where('status', $status);
        }
        // else {
        //     $bids = $bids->where('status', 'pending');
        // }
        return view('buyer.bids.index', compact('bids', 'status'));
        // return view('buyer.bids.test');
    }

    public function getAllBids()
    {
        // get all bids with product details and store of the product
        $bids = Bid::with('product.store')->where('user_id', auth()->user()->id)->get();

        return response()->json([
            'bids' => $bids
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->profile) {
            return response([
                'success' => false,
                'message' => 'Please complete your profile first.',
            ]);
        }
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        $bids = $product->bids;
        $highest_bid = null;
        if ($bids->count() > 0) {
            // the bid with the highest price
            $highest_price = $bids->max('price');
            $highest_bid = $bids->where('price', $highest_price)->first();
        }
        $bid = Bid::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->user()->id,
            'price' => $request->price,
            'status' => 'pending'
        ]);

        if ($bid) {
            // send notification
            $bid->product->store->user->notify(new BidNotification($bid));

            // create notification
            Notification::create([
                'user_id' => $bid->user_id,
                'title' => "You have placed a new bid of $" . $bid->price . " on the item: " . $bid->product->name,
                'is_read' => 0,
            ]);

            Notification::create([
                'user_id' => $bid->product->store->user->id,
                'title' => "You have a new bid of $" . $bid->price . " on the item: " . $bid->product->name,
                'is_read' => 0,
            ]);

            // check if the highest bidder is outbidded by the current user
            if ($highest_bid && $highest_bid->price < $bid->price) {
                // send notification to outbidded user
                $highest_bid->user->notify(new OutBidNotification($bid));

                // create notification
                Notification::create([
                    'user_id' => $highest_bid->user_id,
                    'title' => "You have been outbidded by " . $bid->user->name . " with a bid of $" . $bid->price . " on the item: " . $bid->product->name,
                    'is_read' => 0,
                ]);
            }

            return response([
                'success' => true,
                'message' => 'Bid placed successfully',
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Something went wrong',
            ]);
        }

        // return redirect()->back()->with('success', 'Bid placed successfully');
    }

    public function itemBids($id)
    {
        $bids = Bid::where('product_id', $id)->orderBy('id', 'desc')->get();
        foreach ($bids as $bid) {
            $bid->created = $bid->created_at->diffForHumans();
            $bid->validity = $bid->validity . ' Days';
            $bid->bid_value = number_format($bid->price, 2);
            $bid->userName = $bid->user->name;
        }
        return response()->json($bids);
    }
}
