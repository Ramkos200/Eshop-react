<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'sku_id',
        'sku_code',
        'price',
        'quantity',
        'attributes',

    ];
    protected $casts = ['attributes' => 'array'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }
    public function getRouteKeyName()
    {
        return 'sku_code';
    }
}
