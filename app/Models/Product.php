<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id', 'category_id',
        'name', 
        'slug', // <-- DITAMBAHKAN untuk sinkronisasi dengan migrasi/seeder
        'description',
        'price', 'discount_price',
        'stock', 'min_stock',
        'sku', 'brand', 'condition', 'warranty',
        'weight', 'length', 'width',
        'primary_image', // <-- Menggunakan 'primary_image' sesuai migrasi
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
    
    // Gunakan kolom primary_image yang sesuai dengan database
    protected $primaryImageColumn = 'primary_image';

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

    /**
     * Menghitung rata-rata rating untuk produk ini.
     */
    public function averageRating()
    {
        // Mengambil rating dari kolom 'rating' di tabel reviews
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Accessor untuk mendapatkan URL gambar utama.
     */
    public function getPrimaryImageUrlAttribute()
    {
        $imagePath = $this->{$this->primaryImageColumn};
        
        // If no image stored, return null so views can fallback to default asset
        if (empty($imagePath)) {
            return null;
        }

        // If the stored value already looks like a full URL (seperti dari Seeder), return as-is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // Otherwise, assume it's a storage path and return a public URL
        try {
            return Storage::url($imagePath);
        } catch (\Throwable $e) {
            // Fallback jika Storage::url gagal
            return asset('storage/' . ltrim($imagePath, '/'));
        }
    }

    /**
     * Accessor untuk mendapatkan array URL gambar tambahan.
     */
    public function getAdditionalImageUrlsAttribute()
    {
        $images = $this->additional_images ?? [];
        
        // Pastikan variabel images adalah array
        if (!is_array($images)) {
            // Karena kita menggunakan $casts = ['additional_images' => 'array'], 
            // ini seharusnya tidak diperlukan jika data dari DB sudah benar.
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