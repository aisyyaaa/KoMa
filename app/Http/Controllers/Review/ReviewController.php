<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Wajib: Tambahkan untuk logging

class ReviewController extends Controller
{
    /**
     * Menyimpan Komentar dan Rating untuk sebuah produk (SRS-MartPlace-06).
     */
    public function store(Request $request, Product $product)
    {
        // 1. Validasi Input (Termasuk Komentar Wajib, HP 08/+62)
        try {
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
                'comment' => 'required|string|max:500', 
            ]);
        } catch (ValidationException $e) {
            // Jika validasi gagal, kembalikan error ke halaman sebelumnya
            return back()->withErrors($e->errors())->withInput()->withFragment('reviews-section');
        }

        // 2. Batasan Bisnis: Cek Duplikasi Review
        $existingReview = Review::where('product_id', $product->id)
                                 ->where('visitor_email', $validatedData['visitor_email'])
                                 ->first();

        if ($existingReview) {
            return back()->withErrors([
                'visitor_email' => 'Maaf, Anda hanya diperbolehkan memberikan ulasan satu kali untuk produk ini.'
            ])->withInput()->withFragment('reviews-section');
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
                // REVISI KRITIS: MENGHAPUS 'reviewed_at' karena tidak ada di tabel database
            ]);
            
            // OPTIONAL: Hitung ulang rating_average di Model Product (Ini penting untuk SRS-04)
            // Lakukan ini setelah review berhasil dibuat
            $product->rating_average = $product->reviews()->avg('rating');
            $product->save();
            
        } catch (\Exception $e) {
            // Log the actual error for debugging locally
            Log::error("Gagal menyimpan review (DB ERROR): " . $e->getMessage());
            // Return generic error message to user
            return back()->with('error', 'Gagal menyimpan komentar. Mohon coba lagi.')->withInput()->withFragment('reviews-section');
        }
        
        // 4. Kirim Notifikasi (SRS-MartPlace-06)
        try {
            Mail::raw('Terima kasih atas komentar dan rating Anda untuk produk ' . $product->name . '!', function ($message) use ($review) {
                 $message->to($review->visitor_email)
                         ->subject('Terima Kasih Atas Review Anda | KoMa Market');
            });
            
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email terima kasih ke " . $review->visitor_email . ": " . $e->getMessage());
        }

        // Redirect ke halaman detail produk dengan pesan sukses
        return redirect()->route('katalog.show', $product->slug)->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.')->withFragment('reviews-section');
    }
}