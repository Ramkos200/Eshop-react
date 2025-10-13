<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Img extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'mime_type',
        'file_size',
        'disk',
        'imageable_type',
        'imageable_id',
        'type',
        'order',
        'alt_text',
        'caption',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the parent imageable model (Category, SKU, Order, etc.)
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL of the image
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Scope for main images
     */
    public function scopeMain($query)
    {
        return $query->where('type', 'main');
    }

    /**
     * Scope for gallery images
     */
    public function scopeGallery($query)
    {
        return $query->where('type', 'gallery');
    }

    /**
     * Scope for variant images
     */
    public function scopeVariant($query)
    {
        return $query->where('type', 'variant');
    }

    /**
     * Scope for receipt images
     */
    public function scopeReceipt($query)
    {
        return $query->where('type', 'receipt');
    }

    /**
     * Scope for specific imageable
     */
    public function scopeForModel($query, $model, $id)
    {
        return $query->where('imageable_type', $model)
            ->where('imageable_id', $id);
    }

    /**
     * Delete file from storage when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($img) {
            if (Storage::disk($img->disk)->exists($img->path)) {
                Storage::disk($img->disk)->delete($img->path);
            }
        });
    }
}