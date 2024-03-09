<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'parent_id',
        'is_read'
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }


    /**
     * Get the sender of a chat / reply
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the replies of a chat / reply
     */
    public function replies()
    {
        return $this->belongsTo($this::class, 'parent_id');
    }

    /**
     * Get the parent chat of the reply
     */
    public function parent()
    {
        return $this->belongsTo($this::class, 'parent_id');
    }
}
