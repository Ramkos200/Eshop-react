<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'user_id',
        'status',
        'total_amount',
        'notes',
        'billing_address',
        'shipping_address',
        'Customer'
    ];

    // Add proper casting for JSON fields
    protected $casts = [
        'billing_address' => 'array',
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

    // Add boot method to generate order code automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_code)) {
                $order->order_code = 'ORD-' . date('Ymd-His') . rand(100, 999);
            }
        });
    }
}