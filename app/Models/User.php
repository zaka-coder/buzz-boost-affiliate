<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'active_role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(BuyerProfile::class);
    }

    public function credit_cards() // credit cards
    {
        return $this->hasMany(BuyerCard::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function shippingAddress()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists'); // wishlists is the pivot table
    }

    public function audit()
    {
        return $this->hasOne(Audit::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function support_tickets()
    {
        return $this->hasMany(Support::class)->whereNull('parent_id');
    }

    public function blocked_stores()
    {
        return $this->belongsToMany(Store::class, 'blocked_users', 'user_id', 'store_id');
    }


    public function senderChats()
    {
        return $this->hasMany(Chat::class, 'sender_id')
            ->whereNull('parent_id');
    }

    public function receiverChats()
    {
        return $this->hasMany(Chat::class, 'receiver_id')
            ->whereNull('parent_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // V2
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
