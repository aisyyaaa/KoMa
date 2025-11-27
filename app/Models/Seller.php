<?php 
// app/Models/Seller.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    
    // Sesuaikan dengan skema tabel dari class diagram (Seller.php)
    protected $fillable = [
        'storeName', 
        'storeDescription', 
        'picName', 
        'picPhone', 
        'picEmail', 
        'picStreet', 
        'picRT', 
        'picRW', 
        'picVillage', 
        'picDistrict', // Tambahan Kecamatan
        'picCity', 
        'picProvince', 
        'picKtpNumber', 
        'picPhotoPath', 
        'picKtpFilePath',
        'status',
    ];

}