<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'image',
        'stripe_customer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
