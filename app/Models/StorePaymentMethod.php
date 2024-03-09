<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'name', 'email', 'status', 'key', 'secret'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
