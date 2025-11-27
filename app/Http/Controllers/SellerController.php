<?php 
// app/Http/Controllers/SellerController.php

namespace App\Http\Controllers;
use App\Models\Seller; // Import model yang sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload file

class SellerController extends Controller
{
    /**
     * Tampilkan formulir registrasi penjual (create()::view).
     */
    public function create()
    {
        return view('seller.register');
    }

    /**
     * Simpan data registrasi penjual ke database (store()::redirect).
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $validated = $request->validate([
            // INFORMASI TOKO
            'storeName' => 'required|string|max:100|unique:sellers',
            'storeDescription' => 'nullable|string|max:255',

            // DATA PIC (KONTAK & IDENTITAS)
            'picName' => 'required|string|max:255',
            
            // Nomor Telepon: Harus unik dan format Indonesia (diawali 0 atau +62)
            'picPhone' => [
                'required',
                'string',
                'max:15',
                'unique:sellers',
                'regex:/^(\+62|0)[0-9]{9,13}$/', 
            ],
            
            // Email: Harus email valid dan unik
            'picEmail' => 'required|email|max:255|unique:sellers',
            
            // Nomor KTP: Wajib 16 digit angka (fix-length)
            'picKtpNumber' => 'required|string|digits:16|unique:sellers',
            
            // ALAMAT
            'picStreet' => 'required|string|max:255',
            // RT/RW: Dibatasi maksimal 3 angka (misal 999) dan tidak boleh terlalu panjang
            'picRT' => 'required|string|max:3', 
            'picRW' => 'required|string|max:3',
            
            'picVillage' => 'required|string|max:100',
            'picDistrict' => 'required|string|max:100',
            'picCity' => 'required|string|max:100',
            'picProvince' => 'required|string|max:100',

            // FILE UPLOAD
            // picPhoto: Wajib gambar, max 2MB, hanya jpg/png
            'picPhoto' => 'required|image|mimes:jpg,png|max:2048',
            // picKtpFile: Bisa file atau gambar, max 5MB, format umum (jpg, png, pdf)
            'picKtpFile' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 2. Proses Upload File
        $picPhotoPath = null;
        if ($request->hasFile('picPhoto')) {
            // Simpan di storage/app/public/seller_photos
            $picPhotoPath = $request->file('picPhoto')->store('seller_photos', 'public');
        }

        $picKtpFilePath = null;
        if ($request->hasFile('picKtpFile')) {
            // Simpan di storage/app/public/seller_ktp
            $picKtpFilePath = $request->file('picKtpFile')->store('seller_ktp', 'public');
        }

        // 3. Simpan Data ke Database
        Seller::create([
            'storeName' => $validated['storeName'],
            'storeDescription' => $validated['storeDescription'],
            'picName' => $validated['picName'],
            'picPhone' => $validated['picPhone'],
            'picEmail' => $validated['picEmail'],
            'picStreet' => $validated['picStreet'],
            'picRT' => $validated['picRT'],
            'picRW' => $validated['picRW'],
            'picVillage' => $validated['picVillage'],
            'picDistrict' => $validated['picDistrict'], // Tambahan Kecamatan
            'picCity' => $validated['picCity'],
            'picProvince' => $validated['picProvince'],
            'picKtpNumber' => $validated['picKtpNumber'],
            'picPhotoPath' => $picPhotoPath,
            'picKtpFilePath' => $picKtpFilePath,
            'status' => 'PENDING', // Default status [cite: 235]
        ]);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('katalog.index')
            ->with('is_registered_flag', true) 
            ->with('success', 'Registrasi penjual berhasil. Silakan tunggu proses verifikasi.');
    }
}