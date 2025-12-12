<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute; // Tambahkan untuk Accessor

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // REVISI KRITIS: Mengganti 'role' dengan 'is_platform_admin'
        'is_platform_admin',
        'province',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // REVISI: Tambahkan casting untuk kolom platform admin
        'is_platform_admin' => 'boolean', 
    ];

    // --- LOGIC AUTHENTICATION ---

    /**
     * Return true when the user is a platform (master admin).
     * REVISI: Menggunakan kolom is_platform_admin (boolean)
     */
    public function isPlatformAdmin(): bool
    {
        return $this->is_platform_admin;
    }

    /*
     * Hapus fungsi isAdmin() karena fungsinya duplikat dengan isPlatformAdmin()
     * dan kolom 'role' sudah tidak ada di $fillable.
     * Jika Anda memang ingin mendukung banyak peran, Anda harus menggunakan kolom 'role'
     * dan menghapus is_platform_admin. Saya berasumsi Anda hanya butuh satu peran: Admin Platform.
     */
}