<?php

namespace App\Http\Controllers\Review; // <-- INI DIREVISI: Sesuai struktur folder Anda

use App\Http\Controllers\Controller; // Jangan lupa import Controller dasar
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller // Nama class tetap ReviewController
{
    /**
     * Menyimpan Komentar dan Rating untuk sebuah produk (SRS-MartPlace-06).
     */
    public function store(Request $request, Product $product)
    {
        // 1. Validasi Input (Termasuk Komentar Wajib, HP 08/+62)
        $validatedData = $request->validate([
            'visitor_name' => 'required|string|max:100',
            
            // Regex: Harus dimulai dengan 08 ATAU +628, min 9, max 15
            'visitor_phone' => [
                'required', 
                'string', 
                'min:9', 
                'max:15', 
                'regex:/^(08|\+628)[0-9]{7,13}$/', 
            ],
            
            'visitor_email' => 'required|email|max:255',
            'province' => 'required|string|max:100', 
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500', // Komentar Wajib
        ]);

        // 2. Batasan Bisnis: Cek Duplikasi Review (Buzzer Check)
        $existingReview = Review::where('product_id', $product->id)
                                ->where('visitor_email', $validatedData['visitor_email'])
                                ->first();

        if ($existingReview) {
            throw ValidationException::withMessages([
                'visitor_email' => ['Maaf, Anda hanya diperbolehkan memberikan ulasan satu kali untuk produk ini.']
            ]);
        }
        
        // 3. Simpan Review ke Database
        try {
            $review = Review::create([
                'product_id' => $product->id,
                'visitor_name' => $validatedData['visitor_name'],
                'visitor_phone' => $validatedData['visitor_phone'],
                'visitor_email' => $validatedData['visitor_email'],
                'province' => $validatedData['province'], 
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment'],
                'reviewed_at' => now(), 
            ]);
        } catch (\Exception $e) {
            \Log::error("Gagal menyimpan review: " . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan komentar. Mohon coba lagi.')->withInput();
        }
        
        // 4. Kirim Notifikasi (SRS-MartPlace-06)
        try {
            Mail::raw('Terima kasih atas komentar dan rating Anda untuk produk ' . $product->name . '!', function ($message) use ($review, $product) {
                $message->to($review->visitor_email)
                        ->subject('Terima Kasih Atas Review Anda | KoMa Market');
            });
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email terima kasih ke " . $review->visitor_email . ": " . $e->getMessage());
        }

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.')->withFragment('reviews-section');
    }
}