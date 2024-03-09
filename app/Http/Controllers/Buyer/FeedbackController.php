<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Order;
use App\Traits\RatingTrait;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    use RatingTrait;
    
    public function index()
    {
        $user = auth()->user();
        $feedbacks = Feedback::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        // count positive and negative feedbacks
        $positive = $feedbacks->where('nature', 'positive')->count();
        $negative = $feedbacks->where('nature', 'negative')->count();
        $neutral = $feedbacks->where('nature', 'neutral')->count();

        $ratings = null;
        if (!$feedbacks->isEmpty()) {
            $ratings = $this->ratingPercentage($feedbacks); // rating percentage from RatingTrait
        }

        return view('buyer.feedback.index', compact('feedbacks', 'positive', 'negative', 'neutral', 'ratings'));
    }

    public function awaitingFeedbackProducts()
    {
        // get user
        $user = auth()->user();

        // extract all the feedback products from user
        $feedbacks = Feedback::where('user_id', $user->id)->pluck('product_id')->toArray();

        // extract all the products from user orders which has no feedback
        $orders = Order::where('user_id', $user->id)
            ->where('status', 'like', '%paid%')
            // ->whereNotIn('product_id', $feedbacks)
            ->get();

        // extract products from orders and merge in $products
        $products = collect([]);
        if($orders->count() > 0) {
            foreach ($orders as $order) {
                $product = $order->products->whereNotIn('id', $feedbacks)->first();
                if(!$product) {
                    continue;
                }
                $products->push($product);
            }
        }
        // dd($products);

        // return view with products
        return view('buyer.feedback.awaiting-feedback', compact('products'));
    }

    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required',
        ]);

        // store feedback
        $feedback = new Feedback();
        $feedback->user_id = auth()->user()->id;
        $feedback->product_id = $request->product_id;
        $feedback->nature = $request->rating;
        $feedback->feedback = $request->feedback;
        $feedback->save();

        return redirect()->back()->with('success', 'Feedback submitted successfully');
    }
}
