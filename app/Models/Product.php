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
}