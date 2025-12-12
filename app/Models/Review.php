<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    // HAPUS 'reviewed_at' dari fillable
    protected $fillable = ['product_id', 'visitor_name', 'visitor_phone', 'visitor_email', 'rating', 'comment', 'province'];
    
    /**
     * Casting atribut ke tipe bawaan.
     */
    protected $casts = [
        // HAPUS 'reviewed_at' dari casts
        'rating' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}