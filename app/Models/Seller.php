<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignable).
     */
    protected $fillable = [
<<<<<<< Updated upstream
        // Data Toko & PIC
        'store_name', 
        'store_description', // Pastikan koma di sini
        'pic_name', // Pastikan koma di sini
        'pic_phone', // <-- PERIKSA BARIS SEBELUMNYA DAN BARIS INI
        'pic_email', 
        'pic_ktp_number',
        
        // Detail Alamat
        'pic_street', 
        'pic_rt', 
        'pic_rw', 
        'pic_village', 
        'pic_district', 
        'pic_city', 
        'pic_province', 
        
        // Dokumen
        'pic_photo_path', 
        'pic_ktp_file_path',
        
        // Status & Akun
        'status', 
        'password', // Baris ini tidak perlu koma (kecuali ada elemen lain setelahnya)
=======
        'store_name', 'store_description',
        'pic_name', 'pic_email', 'pic_phone', 'pic_ktp_number',
        'pic_street', 'pic_rt', 'pic_rw', 'pic_village', 'pic_district', 'pic_city', 'pic_province',
        'status', 'password', 'pic_photo_path', 'pic_ktp_file_path'
>>>>>>> Stashed changes
    ];

    /**
     * Kolom yang harus disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];
    
    /**
     * Atribut yang harus di-cast ke tipe bawaan.
     */
    protected $casts = [
        'pic_ktp_number' => 'string',
        'pic_ktp_file_path' => 'string',
        'pic_phone' => 'string',
    ];

    /**
     * Relasi dengan Produk.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}