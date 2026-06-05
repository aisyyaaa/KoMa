<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan form pilihan pendaftaran: Pengguna atau Penjual.
     * 💡 METHOD BARU untuk memperbaiki error rute 'register.choice'.
     */
    public function showRegisterChoice()
    {
        // Mengarahkan ke view yang menampilkan dua tombol: Daftar Pengguna / Daftar Penjual
        return view('auth.register_choice'); 
    }

    /**
     * Tampilkan halaman pilihan login/daftar.
     * Sekarang diarahkan ke halaman yang menampilkan pilihan (Jika ada)
     * atau langsung ke login pengguna umum jika tidak ada pilihan login lain.
     */
    public function showLoginSelection()
    {
        // Jika Anda memiliki satu halaman yang menampilkan pilihan:
        // return view('auth.login-selection');

        // Untuk saat ini, kita akan arahkan ke login Penjual yang sudah direvisi, 
        // karena di dalamnya sudah ada link ke halaman pilihan daftar.
        return redirect()->route('seller.auth.login');
    }

    /**
     * Tampilkan form pendaftaran pembeli minimal.
     */
    public function showBuyerRegister()
    {
        return view('auth.buyer-register');
    }

    /**
     * Handle SHARED login untuk Platform Admin dan Regular User (Buyer).
     * Logika Seller dihapus karena sudah ditangani di SellerAuthController
     * atau harus login melalui rute spesifik seller.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $data['email'];
        $password = $data['password'];

        // --- 1) Platform admin & Regular user (users table) ---
        // Kita gunakan guard 'web' (default) yang mencakup user biasa dan admin
        if (Auth::attempt(['email' => $email, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect berdasarkan peran (asumsi is_platform_admin ada di tabel users)
            if ($user->is_platform_admin ?? false) {
                return redirect()->intended(route('platform.dashboard')); // SRS-MartPlace-07
            }
            
            // Redirect untuk Pengunjung/Buyer
            return redirect()->intended(route('katalog.index')); 
        }

        // --- 2) Seller (Jika Anda tetap ingin shared login di sini, gunakan guard 'seller') ---
        // Logika login Seller seharusnya DITANGANI OLEH SellerAuthController untuk mengecek is_active.
        // Namun, jika Anda bersikeras, ini adalah cara yang benar:
        if (Auth::guard('seller')->attempt(['email' => $email, 'password' => $password], $request->boolean('remember'))) {
            $seller = Auth::guard('seller')->user();
            
            // Pengecekan status aktif akun wajib (SRS-MartPlace-02)
            if (!$seller->is_active) {
                Auth::guard('seller')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages(['email' => ['Akun Penjual belum aktif/diverifikasi.']]);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('seller.dashboard'));
        }


        // Login Gagal Total
        throw ValidationException::withMessages([
            'email' => ['Email atau password tidak valid untuk semua jenis akun.'],
        ]);
    }

    /**
     * Tangani pendaftaran pembeli minimal.
     * (SRS-MartPlace-06 tidak mewajibkan pembeli terdaftar, tapi ini opsional)
     */
    public function registerBuyer(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30', // Nomor HP digunakan saat komentar/rating (SRS-MartPlace-06)
            'email' => 'required|email|max:255|unique:users,email',
        ]);

        $password = Str::random(12);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            // Asumsi field is_platform_admin defaultnya false
        ]);

        // Opsional: Langsung login setelah daftar
        // Auth::login($user); 

        return redirect()->route('login')->with('status', "Akun pembeli dibuat. Password Anda adalah: $password (Segera ganti).");
    }
}