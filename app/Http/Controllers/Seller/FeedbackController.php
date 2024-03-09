<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Traits\RatingTrait;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    use RatingTrait;

    public function index()
    {
        $store = auth()->user()->store;
        $products = $store->products;
        $feedbacks = collect([]);
        foreach ($products as $product) {
            $feedbacks = $feedbacks->merge($product->feedbacks);
        }
        // dd($feedbacks);

        // count positive and negative feedbacks
        $positive = $feedbacks->where('nature', 'positive')->count();
        $negative = $feedbacks->where('nature', 'negative')->count();
        $neutral = $feedbacks->where('nature', 'neutral')->count();

        $ratings = null;
        if (!$feedbacks->isEmpty()) {
            $ratings = $this->ratingPercentage($feedbacks); // rating percentage from RatingTrait
        }

        return view('seller.feedback.index', compact('feedbacks', 'positive', 'negative', 'neutral', 'ratings'));
    }
}
