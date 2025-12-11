<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller; 
// Hapus 'use Illuminate\Support\Facades\Mail;' jika belum digunakan di sini

class SellerVerificationController extends Controller
{
    /**
     * Menampilkan halaman yang menunjukkan status verifikasi penjual (Halaman Tunggu).
     */
    public function show()
    {
        // Mendapatkan data penjual yang sedang login (jika ada)
        $seller = null;
        if (Auth::guard('seller')->check()) {
            $seller = Auth::guard('seller')->user();
        }

        // Tampilkan view status
        return view('seller.auth.verify_status', ['seller' => $seller]);
    }

    /**
     * Placeholder untuk method verify/resend (belum diimplementasikan)
     */
    // public function verify(Request $request)
    // {
    //     // ... logika verifikasi link di email jika diperlukan
    // }

    // public function resend(Request $request)
    // {
    //     // ... logika kirim ulang email
    // }
}