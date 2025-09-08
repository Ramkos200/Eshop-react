<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $fillable = [
        'product_id',
        'code',
        'price',
        'inventory',
        'attributes'

    ];
    protected $casts = ['attributes' => 'array'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
