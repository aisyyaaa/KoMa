<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash; // Tambahkan untuk hash password
use Illuminate\Support\Facades\DB; // Tambahkan untuk transaksi database
use App\Models\Seller; // Import model Seller
use Illuminate\Support\Str; // Tetap pertahankan jika ada keperluan lain

class SellerAuthController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran penjual (SRS-MartPlace-01).
     */
    public function showRegister()
    {
        return view('seller.auth.register');
    }

    /**
     * Memproses pendaftaran penjual (SRS-MartPlace-01).
     */
    public function register(Request $request)
    {
        // 1. Validasi Data Lengkap (Menggunakan field name dari form Anda)
        $data = $request->validate([
            // Data Akun & Login (Password harus ada di sini)
            'email' => 'required|email|max:255|unique:sellers,email',
            'password' => 'required|string|min:8|confirmed', // Validasi Password
            
            // Data Toko & PIC (menggunakan nama field dari form)
            'nama_toko' => 'required|string|max:255|unique:sellers,store_name',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'nama_pic' => 'required|string|max:255',
            'no_ktp_pic' => 'required|string|max:20|unique:sellers,ktp_number', // Disesuaikan dengan NIK 16-20
            'no_hp_pic' => 'required|string|max:15|unique:sellers,phone_number',
            
            // Data Alamat (sesuai field name yang digunakan di form)
            'alamat_pic' => 'required|string|max:255',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'nama_kelurahan' => 'required|string|max:100',
            'nama_kecamatan' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'propinsi' => 'required|string|max:100',
            
            // Dokumen File
            'foto_pic' => 'required|file|image|max:2048', // Max 2MB
            'file_ktp_pic' => 'required|file|max:5120|mimes:pdf,jpeg,png,jpg', // Max 5MB
        ]);

        DB::beginTransaction();
        
        try {
            // 2. Handle file uploads
            $photoPath = $request->file('foto_pic')->store('seller_docs/pic_photos', 'public');
            $ktpPath = $request->file('file_ktp_pic')->store('seller_docs/ktp_files', 'public');
        
            // 3. Simpan data ke Model Seller
            Seller::create([
                'store_name' => $data['nama_toko'],
                'short_description' => $data['deskripsi_singkat'] ?? null,
                
                'pic_name' => $data['nama_pic'],
                'email' => $data['email'],
                'phone_number' => $data['no_hp_pic'],
                'ktp_number' => $data['no_ktp_pic'],
                
                'address' => $data['alamat_pic'],
                'rt' => $data['rt'],
                'rw' => $data['rw'],
                'village' => $data['nama_kelurahan'],
                'district' => $data['nama_kecamatan'],
                'city' => $data['kabupaten_kota'],
                'province' => $data['propinsi'],
                
                'pic_photo_path' => $photoPath, // Simpan path relatif public
                'ktp_file_path' => $ktpPath,   // Simpan path relatif public

                // Status Verifikasi (SRS-MartPlace-02)
                'verification_status' => 'pending',
                'is_active' => false,
                'registration_date' => now(),
                'password' => Hash::make($data['password']), // Hash password yang diinput
            ]);
            
            DB::commit();

            // 4. Redirect ke halaman status verifikasi
            return redirect()->route('seller.auth.verify')->with('status', 'Pendaftaran berhasil. Akun Anda menunggu verifikasi oleh Platform.');
            
        } catch (\Exception $e) {
            DB::rollback();

            // Jika file sudah terupload tapi gagal simpan DB, hapus file
            if (isset($photoPath)) Storage::disk('public')->delete($photoPath);
            if (isset($ktpPath)) Storage::disk('public')->delete($ktpPath);
            
            // Catat ke log untuk debugging detail
            logger()->error('Gagal Registrasi Penjual (Exception):', ['message' => $e->getMessage()]);

            // Tampilkan error umum ke user
            return redirect()->back()->withInput()->withErrors(['general' => 'Gagal mendaftar. Terjadi kesalahan sistem. Silakan coba lagi dengan data yang berbeda.']);
        }
    }

    // ... (Method showLogin, login, logout, dsb. tetap sama)

    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        // implement login logic (harus diperbaiki agar sesuai dengan guard 'seller')
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('seller')->attempt($credentials, $request->filled('remember'))) {
            // Pengecekan status aktif (SRS-02)
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