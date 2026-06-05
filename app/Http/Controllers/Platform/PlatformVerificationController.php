<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder; // Import Builder

class PlatformVerificationController extends Controller
{
    /**
     * Display a listing of sellers awaiting verification.
     */
    public function index(Request $request) 
    {
        $searchQuery = trim($request->get('search')); // FIX 1: Ambil dan bersihkan query pencarian
        
        $query = Seller::where('status', 'PENDING');

        // FIX 2: Tambahkan Filter Pencarian HANYA JIKA query tidak kosong setelah di-trim
        if (!empty($searchQuery)) { 
            $query->where(function (Builder $q) use ($searchQuery) {
                // Cari di Nama Toko, PIC, Email, atau Nomor Telepon
                $q->where('store_name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('pic_name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $searchQuery . '%')
                  ->orWhere('phone_number', 'like', '%' . $searchQuery . '%');
            });
        }
        
        $pending_sellers = $query->latest()->paginate(10)->withQueryString();
        
        // Kirimkan searchQuery kembali ke view untuk mengisi input form
        return view('platform.verifications.index', compact('pending_sellers', 'searchQuery'));
    }

    /**
     * Display the specified seller's details for verification.
     */
    public function show(Seller $seller)
    {
        // Ensure we are only showing sellers that are actually pending
        if ($seller->status !== 'PENDING') {
            return redirect()->route('platform.verifications.index')->withErrors('This seller is not awaiting verification.');
        }
        return view('platform.verifications.show', compact('seller'));
    }

    /**
     * Update the seller's status (Approve or Reject).
     */
    public function update(Request $request, Seller $seller)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'nullable|string|required_if:action,reject',
        ]);

        if ($request->action === 'approve') {
            $seller->status = 'ACTIVE';
            $seller->save();

            // Send approval email
            $this->sendApprovalEmail($seller);

            return redirect()->route('platform.verifications.index')->with('success', 'Seller has been approved.');
        }

        if ($request->action === 'reject') {
            $seller->status = 'REJECTED';
            $seller->save();

            // Send rejection email
            $this->sendRejectionEmail($seller, $request->rejection_reason);

            return redirect()->route('platform.verifications.index')->with('success', 'Seller has been rejected.');
        }

        return redirect()->back()->withErrors('Invalid action.');
    }

    private function sendApprovalEmail(Seller $seller)
    {
        $subject = "Pendaftaran Toko Anda di KoMa Telah Disetujui";
        $message = "Selamat, {$seller->pic_name}! Pendaftaran toko Anda '{$seller->store_name}' telah kami setujui. Anda sekarang dapat login dan mulai berjualan.";

        // Best practice: Use a Mailable class and queue this email.
        try {
            Mail::raw($message, function ($mail) use ($seller, $subject) {
                $mail->to($seller->pic_email)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            // Log the error if mail fails
            \Log::error("Failed to send approval email to {$seller->pic_email}: " . $e->getMessage());
        }
    }

    private function sendRejectionEmail(Seller $seller, $reason)
    {
        $subject = "Pendaftaran Toko Anda di KoMa Ditolak";
        $message = "Mohon maaf, {$seller->pic_name}. Pendaftaran toko Anda '{$seller->store_name}' tidak dapat kami setujui saat ini.\n\nAlasan: {$reason}\n\nSilakan perbaiki data Anda dan coba lagi atau hubungi support.";

        // Best practice: Use a Mailable class and queue this email.
        try {
            Mail::raw($message, function ($mail) use ($seller, $subject) {
                $mail->to($seller->pic_email)
                     ->subject($subject);
            });
        } catch (\Exception $e) {
            // Log the error if mail fails
            \Log::error("Failed to send rejection email to {$seller->pic_email}: " . $e->getMessage());
        }
    }
}