<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Img;

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
    public function images(): MorphMany
    {
        return $this->morphMany(Img::class, 'imageable');
    }

    public function mainImage()
    {
        return $this->morphOne(Img::class, 'imageable')
            ->where('type', 'main')
            ->where('imageable_type', 'App\Models\Sku');
    }

    public function galleryImages()
    {
        return $this->morphMany(Img::class, 'imageable')
            ->where('type', 'variant')
            ->where('imageable_type', 'App\Models\Sku')
            ->orderBy('order');
    }
}