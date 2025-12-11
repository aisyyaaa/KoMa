<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerAuthController extends Controller
{
    public function showRegister()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        // Validate using the form field names (camelCase)
        $data = $request->validate([
            'storeName' => 'required|string|max:255',
            'storeDescription' => 'nullable|string|max:500',
            'picName' => 'required|string|max:255',
            'picKtpNumber' => 'required|string|max:100|unique:sellers,pic_ktp_number',
            'picPhone' => 'required|string|max:50|unique:sellers,pic_phone',
            'picEmail' => 'required|email|max:255|unique:sellers,pic_email',
            'picStreet' => 'required|string|max:255',
            'picRT' => 'required|string|max:10',
            'picRW' => 'required|string|max:10',
            'picVillage' => 'required|string|max:100',
            'picDistrict' => 'required|string|max:100',
            'picCity' => 'required|string|max:100',
            'picProvince' => 'required|string|max:100',
            'picPhoto' => 'required|file|image|max:2048',
            'picKtpFile' => 'required|file|max:5120'
        ]);

        // Handle file uploads
        $photoPath = null;
        $ktpPath = null;
        if ($request->hasFile('picPhoto')) {
            $photoPath = $request->file('picPhoto')->store('seller_photos', 'public');
        }
        if ($request->hasFile('picKtpFile')) {
            $ktpPath = $request->file('picKtpFile')->store('seller_ktp', 'public');
        }

        // Generate a random password — will be communicated upon approval if needed
        $rawPassword = Str::random(10);

        $seller = \App\Models\Seller::create([
            'store_name' => $data['storeName'],
            'store_description' => $data['storeDescription'] ?? null,
            'pic_name' => $data['picName'],
            'pic_email' => $data['picEmail'],
            'pic_phone' => $data['picPhone'],
            'pic_ktp_number' => $data['picKtpNumber'],
            'pic_street' => $data['picStreet'],
            'pic_rt' => $data['picRT'],
            'pic_rw' => $data['picRW'],
            'pic_village' => $data['picVillage'],
            'pic_district' => $data['picDistrict'],
            'pic_city' => $data['picCity'],
            'pic_province' => $data['picProvince'],
            'pic_photo_path' => $photoPath ? ('storage/' . $photoPath) : null,
            'pic_ktp_file_path' => $ktpPath ? ('storage/' . $ktpPath) : null,
            'status' => 'PENDING',
            'password' => bcrypt($rawPassword),
        ]);

        return redirect()->route('seller.register.pending')->with('success', 'Pendaftaran berhasil. Akun Anda menunggu verifikasi oleh admin platform.');
    }

    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        // implement login logic
        return redirect()->route('seller.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
