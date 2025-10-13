<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Img;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'slug',
        'img'

    ];
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function images()
    {
        return $this->morphMany(Img::class, 'imageable');
    }

    public function mainImage()
    {
        return $this->morphOne(Img::class, 'imageable')->where('type', 'main');
    }
}