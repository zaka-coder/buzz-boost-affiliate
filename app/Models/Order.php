<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // protected $guarded = [];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items'); // order_items is the pivot table
    }

    public function shippingProvider()
    {
        return $this->belongsTo(ShippingPreference::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }


}
