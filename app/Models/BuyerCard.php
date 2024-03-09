<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'card_number',
        'cvc',
        'expiry_month',
        'expiry_year',
        'is_verified',
        'default',
        'stripe_customer_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
