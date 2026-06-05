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
<<<<<<< Updated upstream
        // Store / Account
        'store_name',
        'short_description',
        'email',
        'password',

        // PIC (person in charge) and contact
        'pic_name',
        'phone_number',
        'ktp_number', // Kolom ini ada di migrasi

        // Address details
        'address',
        'rt',
        'rw',
        'village',
        'district',
        'city',
        'province',

        // Document paths
        'pic_photo_path',
        'ktp_file_path',

        // Status / verification
        'is_active',
        'status', // <--- KOREKSI KRITIS: Menggunakan 'status' sesuai migrasi dan controller
        'registration_date',
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
        'is_active' => 'boolean', 
        'registration_date' => 'datetime', 
    ];
    
    // Guard yang digunakan untuk otentikasi
    protected $guard = 'seller';

    /**
     * Relasi dengan Produk.
     */
    public function products()
    {
        // Pastikan model Product di-import atau menggunakan namespace penuh jika diperlukan
        return $this->hasMany(Product::class);
    }
}