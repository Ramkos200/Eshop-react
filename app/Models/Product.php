<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Img;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'status',
        'slug',
        'img'

    ];
    protected $appends = ['price_range'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function skus()
    {
        return $this->hasMany(Sku::class);
    }
    public function images(): MorphMany
    {
        return $this->morphMany(Img::class, 'imageable');
    }
    public function mainImage()
    {
        return $this->morphOne(Img::class, 'imageable')
            ->where('type', 'main')
            ->where('imageable_type', 'App\Models\Product');
    }
    public function galleryImages()
    {
        return $this->morphMany(Img::class, 'imageable')
            ->where('type', 'gallery')
            ->where('imageable_type', 'App\Models\Product')
            ->orderBy('sort_order');
    }
    public function getPriceRangeAttribute()
    {
        if ($this->skus->isEmpty()) {
            return 'add variants/skus to get the price range';
        }

        $min = $this->skus->min('price');
        $max = $this->skus->max('price');

        return '$' . number_format($min, 2) . '-$' . number_format($max, 2);
    }
}
