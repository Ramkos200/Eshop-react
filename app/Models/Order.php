<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'user_id',
        'status',
        'total_amount',
        'notes',
        'shipping_address',
        'Customer',
        'payment'
    ];

    // Add proper casting for JSON fields
    protected $casts = [
        'shipping_address' => 'array',
        'Customer' => 'array',
        'total_amount' => 'decimal:2'
    ];

    // Fix the total amount accessor - don't override the stored value
    public function getCalculatedTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName()
    {
        return 'order_code';
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Img::class, 'imageable');
    }

    public function receipts()
    {
        return $this->morphMany(Img::class, 'imageable')
            ->where('type', 'receipt')
            ->orderBy('order');
    }
}