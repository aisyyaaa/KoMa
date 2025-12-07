<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'visitor_name', 'visitor_phone', 'visitor_email', 'rating', 'comment'];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}