<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'listing_type',
        'duration',
        'start',
        'start_time',
        'end_time',
        'item_type',
        'relisting',
        'relist_limit',
        'winner_id',
        'highest_bid_id',
        'reserved',
        'sold',
        'closed',
        'closed_at',
        'relisted',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
