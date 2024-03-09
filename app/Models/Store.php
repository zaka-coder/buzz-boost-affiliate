<?php

namespace App\Models;

use App\Traits\RatingTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    use RatingTrait;

    // protected $fillable = [

    // ];

    protected $guarded = [];

    // public function products()

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ----------------------------------------------------------------------
    // public function shipping() // needs to be removed
    // {
    //     return $this->hasOne(ShippingPreference::class);
    // }
    // ----------------------------------------------------------------------

    public function shippings()
    {
        return $this->hasMany(ShippingPreference::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(StorePaymentMethod::class);
    }

    public function emailNotifications()
    {
        return $this->hasOne(StoreEmailNotification::class);
    }

    public function getRatingsAttribute()
    {
        // extract feedbacks ratings
        $products = $this->products;
        $feedbacks = collect([]);
        foreach ($products as $product) {
            $feedbacks = $feedbacks->merge($product->feedbacks);
        }

        $ratings = null;
        if (!$feedbacks->isEmpty()) {
            $ratings = $this->ratingPercentage($feedbacks); // rating percentage from RatingTrait
        }

        return $ratings;
    }

    public function blocked_users()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'store_id', 'user_id');
    }
}
