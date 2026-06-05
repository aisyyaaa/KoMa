<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use Illuminate\Support\Str;

class SellerAuthController extends Controller
{
    public function showRegister()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255|unique:sellers,email',
            'password' => 'required|string|min:8|confirmed',
            'nama_toko' => 'required|string|max:255|unique:sellers,store_name',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'nama_pic' => 'required|string|max:255',
            'no_ktp_pic' => 'required|string|max:20|unique:sellers,ktp_number',
            'no_hp_pic' => 'required|string|max:15|unique:sellers,phone_number',
            'alamat_pic' => 'required|string|max:255',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'nama_kelurahan' => 'required|string|max:100',
            'nama_kecamatan' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'propinsi' => 'required|string|max:100',
            'foto_pic' => 'required|file|image|max:2048',
            'file_ktp_pic' => 'required|file|max:5120|mimes:pdf,jpeg,png,jpg',
        ]);

        try {
            $photoPath = $request->file('foto_pic')->store('seller_docs/pic_photos', 'public');
            $ktpPath   = $request->file('file_ktp_pic')->store('seller_docs/ktp_files', 'public');

            Seller::create([
                'store_name'          => $data['nama_toko'],
                'short_description'   => $data['deskripsi_singkat'] ?? null,
                'pic_name'            => $data['nama_pic'],
                'email'               => $data['email'],
                'phone_number'        => $data['no_hp_pic'],
                'ktp_number'          => $data['no_ktp_pic'],
                'address'             => $data['alamat_pic'],
                'rt'                  => $data['rt'],
                'rw'                  => $data['rw'],
                'village'             => $data['nama_kelurahan'],
                'district'            => $data['nama_kecamatan'],
                'city'                => $data['kabupaten_kota'],
                'province'            => $data['propinsi'],
                'pic_photo_path'      => $photoPath,
                'ktp_file_path'       => $ktpPath,
                'status'            => 'PENDING',
                'is_active'         => false,
                'registration_date'   => now(),
                'password'            => Hash::make($data['password']),
            ]);

            return redirect()->route('seller.auth.verify')->with('status', 'Pendaftaran berhasil. Akun Anda menunggu verifikasi oleh Platform.');

        } catch (\Exception $e) {
            if (isset($photoPath)) Storage::disk('public')->delete($photoPath);
            if (isset($ktpPath)) Storage::disk('public')->delete($ktpPath);
            logger()->error('Gagal Registrasi Penjual:', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->withErrors(['general' => 'Gagal mendaftar. Terjadi kesalahan sistem.']);
        }
    }

    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('seller')->attempt($credentials, $request->filled('remember'))) {
            if (!Auth::guard('seller')->user()->is_active) {
                Auth::guard('seller')->logout();
                $request->session()->invalidate();
                return redirect()->route('seller.auth.verify')->withErrors(['login_error' => 'Akun belum aktif. Mohon tunggu verifikasi Platform.']);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('seller.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password tidak valid.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        return redirect()->route('katalog.index');
    }
}
