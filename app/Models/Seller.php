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
     * Diselaraskan dengan kolom di migrasi terbaru.
     */
    protected $fillable = [
        // Data Toko & Akun Login
        'store_name', 
        'short_description', // Diselaraskan dari 'store_description'
        'email',           // Diselaraskan dari 'pic_email'
        'password', 
        
        // Data PIC & Kontak
        'pic_name', 
        'phone_number',    // Diselaraskan dari 'pic_phone'
        'ktp_number',      // Diselaraskan dari 'pic_ktp_number'
        
        // Detail Alamat PIC (Termasuk Kecamatan)
        'address',         // Diselaraskan dari 'pic_street'
        'rt', 
        'rw', 
        'village', 
        'district',        // 💡 KOLOM KECAMATAN
        'city', 
        'province', 
        
        // Dokumen PIC
        'pic_photo_path', 
        'ktp_file_path',
        
        // Status & Verifikasi (SRS-MartPlace-02)
        'is_active',           // Status aktif akun (boolean)
        'verification_status', // Status verifikasi (enum: pending, approved, rejected)
        'registration_date',   // Tanggal pendaftaran
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
        'is_active' => 'boolean', // Status aktif adalah boolean
        'registration_date' => 'datetime', // Tanggal pendaftaran adalah datetime
    ];

    /**
     * Relasi dengan Produk.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}