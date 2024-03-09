<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function productListing()
    {
        return $this->hasOne(ProductListing::class);
    }

    public function productPricing()
    {
        return $this->hasOne(ProductPricing::class);
    }

    public function productShipping()
    {
        return $this->hasOne(ProductShipping::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function category()
    {
        // return $this->belongsTo(SubSubCategory::class, 'sub_sub_category_id');
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCountdownTimeAttribute()
    {
        $remaining_time = [];
        $startTime = $this->productListing->start_time;
        $days = $this->productListing->duration;
        $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' + ' . $days));

        $remainingTime = strtotime($endTime) - time();
        // calculate days, hours, minutes and seconds
        $days = floor($remainingTime / 86400);
        $hours = floor(($remainingTime - ($days * 86400)) / 3600);
        $minutes = floor(($remainingTime - ($days * 86400) - ($hours * 3600)) / 60);
        $seconds = floor($remainingTime - ($days * 86400) - ($hours * 3600) - ($minutes * 60));
        // fill in remaining_time array
        $remaining_time['days'] = $days;
        $remaining_time['hours'] = $hours;
        $remaining_time['minutes'] = $minutes;
        $remaining_time['seconds'] = $seconds;
        return $remaining_time; // return array with countdown time
    }

    public function getIsPublishedAttribute()
    {
        // check the start time if it is less than current time then return true otherwise false
        return $this->productListing->start_time <= date('Y-m-d H:i:s');
    }

    public function getIsClosedAttribute()
    {
        // check the end time if it is less than current time then return true otherwise false
        return $this->productListing->end_time < date('Y-m-d H:i:s');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function wishlistUser()
    {
        return $this->belongsToMany(User::class, 'wishlists'); // wishlists is the pivot table
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items'); // order_items is the pivot table
    }

    public function audit()
    {
        return $this->hasOne(Audit::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    // V2
    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}
