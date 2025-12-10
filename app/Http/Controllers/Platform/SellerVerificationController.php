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
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_platform_admin) {
                return redirect()->route('platform.auth.login');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $pending = Seller::where('status', 'PENDING')->orderBy('created_at', 'desc')->get();
        return view('platform.verifications.index', compact('pending'));
    }

    public function show(Seller $seller)
    {
        return view('platform.verifications.show', compact('seller'));
    }

    public function approve(Request $request, Seller $seller)
    {
        try {
            $seller->status = 'ACTIVE';
            $seller->save();

            // send approval email
            Mail::to($seller->pic_email)->send(new SellerApprovedMail($seller));

            return redirect()->route('platform.verifications.sellers.index')->with('success', 'Penjual telah diverifikasi dan email dikirim.');
        } catch (\Exception $e) {
            Log::error('Seller approval error: '.$e->getMessage());
            return back()->with('error', 'Gagal memverifikasi penjual.');
        }
    }

    public function reject(Request $request, Seller $seller)
    {
        $reason = $request->input('reason');
        try {
            $seller->status = 'REJECTED';
            $seller->save();

            Mail::to($seller->pic_email)->send(new SellerRejectedMail($seller, $reason));

            return redirect()->route('platform.verifications.sellers.index')->with('success', 'Penjual ditolak dan notifikasi dikirim.');
        } catch (\Exception $e) {
            Log::error('Seller rejection error: '.$e->getMessage());
            return back()->with('error', 'Gagal menolak penjual.');
        }
    }
}
