<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use Illuminate\Support\Facades\Mail; // Diperlukan untuk SRS-MartPlace-02 (Notifikasi)

class SellerAuthController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran penjual (SRS-MartPlace-01).
     */
    public function showRegister()
    {
        // Pastikan path view ini benar: resources/views/seller/auth/register.blade.php
        return view('seller.auth.register');
    }

    /**
     * Memproses pendaftaran penjual, menyimpan 14 elemen data (SRS-MartPlace-01).
     * Serta mengatur status awal verifikasi (SRS-MartPlace-02).
     */
    public function register(Request $request)
    {
        // --- 1. Validasi Semua 14 Elemen Data (SRS-MartPlace-01) ---
        $request->validate([
            // Data Akun & Login
            'email' => 'required|email|max:255|unique:sellers,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Data Toko & PIC
            'nama_toko' => 'required|string|max:255|unique:sellers,store_name', // 1. Nama toko
            'deskripsi_singkat' => 'nullable|string|max:500', // 2. Deskripsi singkat
            'nama_pic' => 'required|string|max:255', // 3. Nama PIC
            'no_hp_pic' => 'required|string|max:15|unique:sellers,phone_number', // 4. No Handphone PIC
            
            // Detail Alamat PIC
            'alamat_pic' => 'required|string', // 6. Alamat (nama jalan) PIC
            'rt' => 'required|string|max:5', // 7. RT
            'rw' => 'required|string|max:5', // 8. RW
            'nama_kelurahan' => 'required|string|max:100', // 9. Nama kelurahan (village)
            'nama_kecamatan' => 'required|string|max:100', // District (Kecamatan)
            'kabupaten_kota' => 'required|string|max:100', // 10. Kabupaten/Kota (city)
            'propinsi' => 'required|string|max:100', // 11. Propinsi (province)
            
            // Dokumen PIC
            'no_ktp_pic' => 'required|string|max:20|unique:sellers,ktp_number', // 12. No. KTP PIC
            'foto_pic' => 'required|file|mimes:jpeg,png,jpg|max:2048', // 13. Foto PIC
            'file_ktp_pic' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048', // 14. File upload KTP PIC
        ]);
        
        // --- 2. Proses Penyimpanan Data dan File ---
        DB::beginTransaction();
        try {
            // Logika Upload File
            // Pastikan Anda sudah menjalankan 'php artisan storage:link'
            $fotoPath = $request->file('foto_pic')->store('seller_docs/pic_photos', 'public');
            $ktpPath = $request->file('file_ktp_pic')->store('seller_docs/ktp_files', 'public');

            $seller = Seller::create([
                // Data Akun & Toko
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'store_name' => $request->nama_toko,
                'short_description' => $request->deskripsi_singkat,
                
                // Data PIC & KTP
                'pic_name' => $request->nama_pic,
                'phone_number' => $request->no_hp_pic, // Disesuaikan: phone_number
                'ktp_number' => $request->no_ktp_pic,
                'pic_photo_path' => $fotoPath,
                'ktp_file_path' => $ktpPath,
                
                // Data Lokasi
                'address' => $request->alamat_pic,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'village' => $request->nama_kelurahan, // Disesuaikan: village
                'district' => $request->nama_kecamatan, // Disesuaikan: district (Kecamatan)
                'city' => $request->kabupaten_kota, // Disesuaikan: city
                'province' => $request->propinsi, // Disesuaikan: province

                // Status Verifikasi (SRS-MartPlace-02)
                'is_active' => false, 
                'verification_status' => 'pending', 
                'registration_date' => now(), // Mencatat tanggal dan waktu (datetime)
            ]);

            // --- 3. Notifikasi Verifikasi (SRS-MartPlace-02) ---
            // Kirim notifikasi ke Platform/Admin bahwa ada pendaftaran baru.
            // Kirim notifikasi ke Penjual (email) bahwa pendaftaran sedang diverifikasi.
            // Contoh (Asumsi Anda punya Mailable):
            // Mail::to($seller->email)->send(new SellerRegistrationPendingNotification($seller));

            DB::commit();

            return redirect()->route('seller.auth.verify')
                             ->with('success', 'Pendaftaran berhasil. Akun Anda menunggu verifikasi oleh Platform.');

        } catch (\Exception $e) {
            DB::rollback();
            // Anda bisa log $e->getMessage() di sini
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal mendaftar. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan formulir login penjual.
     */
    public function showLogin()
    {
        return view('seller.auth.login');
    }

    /**
     * Memproses login penjual.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([ 
            'email' => 'required|email', 
            'password' => 'required|string' 
        ]);

        if (Auth::guard('seller')->attempt($credentials, $request->boolean('remember'))) {
            $seller = Auth::guard('seller')->user();
            
            // Cek status aktif (Sesuai deskripsi: Akun dikelola oleh platform untuk status aktif/tidak)
            if (!$seller->is_active) {
                Auth::guard('seller')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Notifikasi disesuaikan untuk menunjukkan status verifikasi
                return redirect()->route('seller.auth.verify')
                                 ->withErrors(['login_error' => 'Akun Anda belum aktif. Status saat ini: ' . ucfirst($seller->verification_status) . '. Mohon tunggu aktivasi dari Platform.']);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('seller.dashboard'));
        }

        throw ValidationException::withMessages(['email' => ['Email atau password yang Anda masukkan tidak valid.']]);
    }

    /**
     * Logout penjual.
     */
    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('katalog.index');
    }
}