<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'seller_id', 'category_id',
        'name', 'description',
        'price', 'discount_price',
        'stock', 'min_stock',
        'sku', 'brand', 'condition', 'warranty',
        'weight', 'length', 'width',
        'primary_images', 'additional_images'
    ];

    protected $casts = [
        'additional_images' => 'array',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getPrimaryImageUrlAttribute()
    {
        // If no image stored, return null so views can fallback to default asset
        if (empty($this->primary_images)) {
            return null;
        }

        // If the stored value already looks like a full URL, return as-is
        if (filter_var($this->primary_images, FILTER_VALIDATE_URL)) {
            return $this->primary_images;
        }

        // Otherwise, assume it's a storage path and return a public URL
        // Use Storage::url so it respects the configured filesystem
        try {
            return Storage::url($this->primary_images);
        } catch (\Throwable $e) {
            return asset('storage/' . ltrim($this->primary_images, '/'));
        }
    }

    public function getAdditionalImageUrlsAttribute()
    {
        $images = $this->additional_images ?? [];
        if (!is_array($images)) {
            $images = json_decode($images, true) ?: [];
        }

        $urls = [];
        foreach ($images as $img) {
            if (empty($img)) continue;
            if (filter_var($img, FILTER_VALIDATE_URL)) {
                $urls[] = $img;
                continue;
            }
            try {
                $urls[] = Storage::url($img);
            } catch (\Throwable $e) {
                $urls[] = asset('storage/' . ltrim($img, '/'));
            }
        }

        return $urls;
    }

    public function getConditionLabelAttribute()
    {
        return match($this->condition) {
            'new' => 'Baru',
            'used' => 'Bekas',
            default => ucfirst($this->condition ?? ''),
        };
    }
}