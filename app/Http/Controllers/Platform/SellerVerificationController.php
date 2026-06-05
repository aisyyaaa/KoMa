<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerApprovedMail;
use App\Mail\SellerRejectedMail;

class SellerVerificationController extends Controller
{
    public function __construct()
    {
        // Menonaktifkan middleware otentikasi agar bisa diakses tanpa login untuk tujuan testing
        // KARENA ROUTE LOGIN PLATFORM SUDAH DIHAPUS UNTUK TESTING.
        /*
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_platform_admin) {
                return redirect()->route('platform.auth.login');
            }
            return $next($request);
        });
        */
    }

    public function index()
    {
        $sellers = Seller::orderBy('created_at', 'desc')->get();
        return view('platform.verifications.index', compact('sellers'));
    }

    public function show(Seller $seller)
    {
        return view('platform.verifications.show', compact('seller'));
    }

    public function approve(Request $request, Seller $seller)
    {
        // Pencegahan perubahan jika status sudah ACTIVE
        if ($seller->status === 'ACTIVE') {
            return back()->with('error', 'Penjual ini sudah berstatus ACTIVE dan tidak perlu diverifikasi ulang.');
        }
        
        try {
            // Set status menjadi ACTIVE
            $seller->status = 'ACTIVE';
            $seller->is_active = true; // Tambahkan pembaruan is_active
            $seller->save();

            // send approval email
            Mail::to($seller->email)->send(new SellerApprovedMail($seller));

            return redirect()->route('platform.verifications.sellers.index')->with('success', 'Penjual telah diverifikasi dan email aktivasi dikirim.');
        } catch (\Exception $e) {
            Log::error('Seller approval error: '.$e->getMessage());
            return back()->with('error', 'Gagal memverifikasi penjual.');
        }
    }

    public function reject(Request $request, Seller $seller)
    {
        // Pencegahan perubahan jika status sudah ACTIVE
        if ($seller->status === 'ACTIVE') {
            return back()->with('error', 'Status penjual yang sudah DITERIMA (ACTIVE) tidak dapat ditolak.');
        }

        $request->validate(['reason' => 'required|string|max:500']); 
        
        $reason = $request->input('reason');
        try {
            $seller->status = 'REJECTED';
            $seller->is_active = false; // Pastikan akun tidak aktif
            $seller->save();

            Mail::to($seller->email)->send(new SellerRejectedMail($seller, $reason));

            return redirect()->route('platform.verifications.sellers.index')->with('success', 'Penjual ditolak dan notifikasi dikirim.');
        } catch (\Exception $e) {
            Log::error('Seller rejection error: '.$e->getMessage());
            return back()->with('error', 'Gagal menolak penjual.');
        }
    }
    
    // FUNGSI INI DIGUNAKAN UNTUK DROPDOWN STATUS DI HALAMAN INDEX (platform.verifications.sellers.status)
    public function updateStatus(Request $request, Seller $seller)
    {
        $newStatus = $request->input('status');
        $currentStatus = $seller->status;
        
        // --- 1. Pengecekan Integritas Data ---
        
        // Aturan A: ACTIVE tidak boleh diubah ke REJECTED atau PENDING
        if ($currentStatus === 'ACTIVE' && $newStatus !== 'ACTIVE') {
            return back()->with('error', 'Status penjual yang sudah DITERIMA (ACTIVE) tidak dapat diubah kembali.');
        }

        // Aturan B: REJECTED tidak boleh diubah ke PENDING
        if ($currentStatus === 'REJECTED' && $newStatus === 'PENDING') {
            return back()->with('error', 'Status DITOLAK (REJECTED) tidak dapat diubah kembali menjadi Menunggu (PENDING).');
        }
        
        // --- 2. Validasi & Penyimpanan ---
        
        $data = $request->validate([
            'status' => 'required|in:PENDING,ACTIVE,REJECTED',
        ]);

        $seller->status = $data['status'];
        // Atur is_active sesuai status: hanya ACTIVE yang boleh true
        $seller->is_active = $data['status'] === 'ACTIVE';
        
        $seller->save();
        
        // --- 3. Logika Notifikasi Email ---
        
        if ($seller->status === 'REJECTED') {
            // Mengirim notifikasi penolakan (alasan default)
            Mail::to($seller->email)->send(new SellerRejectedMail($seller, 'Mohon lengkapi kembali data anda.'));
            return back()->with('success', 'Status diperbarui menjadi DITOLAK dan email penolakan dikirim.');
        }
        
        // Jika status diubah ke ACTIVE dari PENDING, kirim email aktivasi
        if ($seller->status === 'ACTIVE' && $currentStatus !== 'ACTIVE') {
             Mail::to($seller->email)->send(new SellerApprovedMail($seller));
        }

        return back()->with('success', 'Status penjual berhasil diperbarui.');
    }

    // FUNGSI INI DIGUNAKAN UNTUK TOMBOL KIRIM VIA EMAIL (platform.verifications.sellers.notify)
    public function sendActivationEmail(Seller $seller)
    {
        if ($seller->status !== 'ACTIVE') {
            return back()->with('error', 'Status penjual belum DITERIMA (ACTIVE). Email aktivasi gagal dikirim.');
        }

        try {
            Mail::to($seller->email)->send(new SellerApprovedMail($seller));
            return back()->with('success', 'Email aktivasi telah dikirim ulang ke penjual.');
        } catch (\Exception $e) {
            Log::error('Send activation email error: '.$e->getMessage());
            return back()->with('error', 'Gagal mengirim email aktivasi. Cek konfigurasi mail server.');
        }
    }
}