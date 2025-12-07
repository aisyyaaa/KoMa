<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable
{
    protected $fillable = [
        'store_name', 'pic_name', 'pic_email', 'pic_phone', 'pic_ktp_number',
        'status', 'password', 'pic_photo_path', 'pic_ktp_file_path'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}