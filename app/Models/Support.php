<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'category', 'status', 'user_role', 'user_id', 'parent_id', 'attachment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Support::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Support::class, 'parent_id');
    }
}
