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
        // Store / Account
        'store_name',
        'short_description',
        'email',
        'password',

        // PIC (person in charge) and contact
        'pic_name',
        'phone_number',
        'ktp_number',

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
        'verification_status',
        'registration_date',
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