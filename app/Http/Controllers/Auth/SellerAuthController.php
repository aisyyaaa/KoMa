<?php

namespace App\Http\Controllers\Auth; // Namespace: App\Http\Controllers\Auth

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Seller; // Pastikan model ini tersedia

class SellerAuthController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran penjual.
     */
    public function showRegister()
    {
        return view('seller.auth.register');
    }

    /**
     * Memproses pendaftaran penjual, termasuk Kecamatan,
     * dan memastikan syntax array validasi sudah benar.
     */
    public function register(Request $request)
    {
        // --- 1. Validasi Data Lengkap (BEBAS SYNTAX ERROR) ---
        // Menggunakan => (tanda panah) BUKAN : (titik dua)
        $request->validate([
            // Data Akun & Login
            'email' => 'required|email|max:255|unique:sellers,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Data Toko & PIC
            'nama_toko' => 'required|string|max:255|unique:sellers,store_name',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'nama_pic' => 'required|string|max:255',
            'no_hp_pic' => 'required|string|max:15|unique:sellers,phone_number',
            'alamat_pic' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'nama_kelurahan' => 'required|string|max:100',
            'nama_kecamatan' => 'required|string|max:100', // Termasuk Kecamatan
            'kabupaten_kota' => 'required|string|max:100',
            'propinsi' => 'required|string|max:100',
            
            // Dokumen PIC
            'no_ktp_pic' => 'required|string|max:20|unique:sellers,ktp_number',
            'foto_pic' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'file_ktp_pic' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);
        
        // --- 2. Proses Penyimpanan Data (Tetap Sama) ---
        DB::beginTransaction();
        try {
            // Logika Upload File
            $fotoPath = $request->file('foto_pic')->store('seller_docs/pic_photos', 'public');
            $ktpPath = $request->file('file_ktp_pic')->store('seller_docs/ktp_files', 'public');

            Seller::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'store_name' => $request->nama_toko,
                'short_description' => $request->deskripsi_singkat,
                'pic_name' => $request->nama_pic,
                'phone_number' => $request->no_hp_pic,
                'ktp_number' => $request->no_ktp_pic,
                'pic_photo_path' => $fotoPath,
                'ktp_file_path' => $ktpPath,
                
                // Data Lokasi
                'address' => $request->alamat_pic,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'village' => $request->nama_kelurahan,
                'district' => $request->nama_kecamatan, // Disimpan sebagai 'district'
                'city' => $request->kabupaten_kota,
                'province' => $request->propinsi,

                // Status Verifikasi (SRS-MartPlace-02)
                'is_active' => false, 
                'verification_status' => 'pending', 
                'registration_date' => now(), 
            ]);

            DB::commit();

            return redirect()->route('seller.auth.verify')
                             ->with('success', 'Pendaftaran berhasil. Akun Anda menunggu verifikasi oleh Platform.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal mendaftar. Silakan coba lagi.']);
        }
    }

    // --- (Method showLogin, login, dan logout tetap sama) ---
    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([ 
            'email' => 'required|email', 
            'password' => 'required|string' 
        ]);

        if (Auth::guard('seller')->attempt($credentials, $request->boolean('remember'))) {
            $seller = Auth::guard('seller')->user();
            
            if (!$seller->is_active) {
                Auth::guard('seller')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('seller.auth.verify')
                                 ->withErrors(['login_error' => 'Akun Anda belum aktif. Mohon tunggu verifikasi dari Platform.']);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('seller.dashboard'));
        }

        throw ValidationException::withMessages(['email' => ['Email atau password yang Anda masukkan tidak valid.']]);
    }

    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('katalog.index');
    }
}