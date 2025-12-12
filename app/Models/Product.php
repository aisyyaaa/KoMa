<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute; 

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
        
        // REVISI KRITIS: Tambahkan kolom Pengiriman
        'shipment_origin_city', 
        'base_shipping_cost', 
        
        // Gambar & Rating
        'primary_image', 
        'additional_images',
        'rating_average', 
        'is_active'
    ];

    protected $casts = [
        'additional_images' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        
        // REVISI KRITIS: Cast Biaya Kirim Dasar
        'base_shipping_cost' => 'decimal:2',
        
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'warranty' => 'integer',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];
    
    // Tambahkan accessor URL gambar utama ke Appends
    protected $appends = ['primary_image_url', 'condition_label']; 

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
     */
    protected function primaryImageUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // Membaca kolom DATABASE ASLI: 'primary_image'
                $imagePath = $attributes['primary_image'] ?? null; 
                
                if (empty($imagePath)) {
                    // Fallback ke aset default jika path kosong
                    return asset('images/default_product.png'); 
                }

                // Skenario 1: Path sudah berupa URL lengkap (Seeder)
                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                    return $imagePath;
                }

                // Skenario 2: File Path Lokal (Upload)
                return Storage::url($imagePath);
            },
        );
    }

    /**
     * Accessor untuk mendapatkan array URL gambar tambahan.
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
            
            $urls[] = Storage::url($img);
        }

        return $urls;
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