<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute; // Tambahkan ini untuk Accessor baru

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'category_id',
        'name', 
        'slug',
        'description',
        'price', 'discount_price',
        'stock', 'min_stock',
        'sku', 'brand', 'condition', 'warranty',
        'weight', 'length', 'width',
        'primary_image',
        'additional_images'
    ];

    protected $casts = [
        'additional_images' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'warranty' => 'integer',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];
    
    // Tambahkan accessor URL gambar utama ke Appends agar selalu tersedia di Collection/JSON
    protected $appends = ['primary_image_url', 'average_rating'];

    // --- RELATIONS ---

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

    // --- ACCESSORS / VIRTUAL ATTRIBUTES ---

    /**
     * ACCESSOR untuk mendapatkan URL gambar utama.
     * Menggunakan Laravel 9/10/11 Attribute untuk sintaks modern.
     */
    protected function primaryImageUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $imagePath = $attributes['primary_image'];
                
                if (empty($imagePath)) {
                    // Fallback ke aset default jika gambar tidak ada
                    return asset('images/default_product.png'); 
                }

                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                    return $imagePath;
                }

                try {
                    return Storage::url($imagePath);
                } catch (\Throwable $e) {
                    return asset('storage/' . ltrim($imagePath, '/'));
                }
            },
        );
    }

    /**
     * ACCESSOR untuk mendapatkan array URL gambar tambahan.
     * Tidak diubah dari kode Anda, hanya menggunakan sintaks fungsi.
     */
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


    /**
     * ACCESSOR/HELPER untuk mendapatkan rata-rata rating (diakses sebagai $product->average_rating).
     * Penting untuk digunakan di View dan untuk mencegah error di Controller saat mengurutkan.
     */
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->reviews()->avg('rating') ?? 0,
        );
    }

    /**
     * Accessor untuk mendapatkan label kondisi yang lebih mudah dibaca.
     */
    public function getConditionLabelAttribute()
    {
        return match($this->condition) {
            'new' => 'Baru',
            'used' => 'Bekas',
            default => ucfirst($this->condition ?? ''),
        };
    }
}