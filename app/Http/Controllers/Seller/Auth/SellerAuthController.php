<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'pic_name' => 'required|string|max:255',
            'pic_email' => 'required|email|max:255|unique:sellers,pic_email',
            'pic_phone' => 'nullable|string|max:50',
            'pic_ktp_number' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $seller = \App\Models\Seller::create([
            'store_name' => $data['store_name'],
            'pic_name' => $data['pic_name'],
            'pic_email' => $data['pic_email'],
            'pic_phone' => $data['pic_phone'] ?? null,
            'pic_ktp_number' => $data['pic_ktp_number'] ?? null,
            'password' => bcrypt($data['password']),
            'status' => 'PENDING'
        ]);

        return redirect()->route('landingpage.index')->with('success', 'Pendaftaran berhasil. Akun Anda menunggu verifikasi oleh admin platform.');
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
