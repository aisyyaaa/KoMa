<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_id', 'visitor_name', 'visitor_phone', 'visitor_email', 'rating', 'comment', 'province', 'reviewed_at'];
    
    /**
     * Casting atribut ke tipe bawaan.
     */
    protected $casts = [
        'reviewed_at' => 'datetime', // <-- INI HARUS ADA!
        'rating' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}