<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
