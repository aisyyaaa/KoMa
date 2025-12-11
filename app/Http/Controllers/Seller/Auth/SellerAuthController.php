<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerAuthController extends Controller
{
    public function showRegister()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string|max:255',
            'pic_name' => 'required|string|max:255',
            'pic_email' => 'required|email|max:255|unique:sellers,pic_email',
            'pic_phone' => 'required|string|max:50',
            'pic_ktp_number' => 'required|string|size:16|unique:sellers,pic_ktp_number',
            'pic_street' => 'required|string|max:255',
            'pic_rt' => 'required|string|max:5',
            'pic_rw' => 'required|string|max:5',
            'pic_village' => 'required|string|max:100',
            'pic_district' => 'required|string|max:100',
            'pic_city' => 'required|string|max:100',
            'pic_province' => 'required|string|max:100',
            'pic_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pic_ktp_file' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $photoPath = $request->file('pic_photo')->store('seller_photos', 'public');
        $ktpPath = $request->file('pic_ktp_file')->store('seller_ktp', 'public');

        $seller = \App\Models\Seller::create([
            'store_name' => $data['store_name'],
            'store_description' => $data['store_description'] ?? null,
            'pic_name' => $data['pic_name'],
            'pic_email' => $data['pic_email'],
            'pic_phone' => $data['pic_phone'],
            'pic_ktp_number' => $data['pic_ktp_number'],
            'pic_street' => $data['pic_street'],
            'pic_rt' => $data['pic_rt'],
            'pic_rw' => $data['pic_rw'],
            'pic_village' => $data['pic_village'],
            'pic_district' => $data['pic_district'],
            'pic_city' => $data['pic_city'],
            'pic_province' => $data['pic_province'],
            'pic_photo_path' => $photoPath,
            'pic_ktp_file_path' => $ktpPath,
            'password' => bcrypt($data['password']),
            'status' => 'PENDING'
        ]);

        return redirect()->route('login')
            ->with('status', 'Pendaftaran berhasil. Silakan tunggu verifikasi dari admin platform sebelum masuk.');
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
