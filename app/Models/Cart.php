<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'guest_token', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeActiveOrConverted($query)
    {
        return $query->whereIn('status', ['active', 'converted']);
    }

    public function scopeForUser($query, $user)
    {
        if ($user) {
            return $query->where('user_id', $user->id);
        }
        return $query;
    }

    public function scopeForGuest($query, $token)
    {
        return $query->where('guest_token', $token);
    }
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }
    public function convertToOrder()
    {
        $this->update(['status' => 'converted']);
    }
}