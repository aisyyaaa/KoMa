<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\DB; 

class SellerReportController extends Controller
{
    // Eager loading umum: HANYA 'category' yang dimuat
    private $eagerLoads = ['category']; 

    /**
     * Tampilkan halaman laporan stok rendah (View HTML) - SRS-MartPlace-14
     */
    public function lowStock(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();

        // Menggunakan JOIN untuk sorting Kategori dan Produk
        $products = Product::where('seller_id', $sellerId)
                                   ->with($this->eagerLoads) 
                                   ->where('stock', '<', 2) 
                                   ->join('categories', 'products.category_id', '=', 'categories.id') 
                                   ->orderBy('categories.name', 'asc') // Urutkan berdasarkan Kategori
                                   ->orderBy('products.name', 'asc')   // Lalu urutkan berdasarkan Produk
                                   ->select('products.*') 
                                   ->paginate(10); 

        return view('seller.reports.low_stock', compact('products')); 
    }

    /**
     * Tampilkan halaman laporan stok diurutkan berdasarkan kuantitas - SRS-MartPlace-12
     */
    public function stockByQuantity(Request $request) 
    {
        $sellerId = Auth::guard('seller')->id();
        
        $products = Product::where('seller_id', $sellerId)
                           ->with($this->eagerLoads)
                           ->withAvg('reviews', 'rating')
                           ->orderBy('stock', 'desc')
                           ->paginate(10);
        
        return view('seller.reports.stock_by_quantity', compact('products'));
    }

    /**
     * Tampilkan halaman laporan stok diurutkan berdasarkan rating - SRS-MartPlace-13
     */
    public function stockByRating(Request $request) 
    {
        $sellerId = Auth::guard('seller')->id();

        $products = Product::where('seller_id', $sellerId)
                           ->with($this->eagerLoads)
                           ->withAvg('reviews', 'rating')
                           ->orderByDesc('reviews_avg_rating') 
                           ->paginate(10);
        
        return view('seller.reports.stock_by_rating', compact('products'));
    }

    /**
     * Fungsi untuk mengekspor laporan Penjual ke PDF (SRS-MartPlace-12, 13, 14)
     */
    public function exportPdf(Request $request, $type)
    {
        $sellerId = Auth::guard('seller')->id();
        $title = '';
        $products = collect();
        $view_path = ''; // Deklarasi view_path di awal

        $baseQuery = Product::where('seller_id', $sellerId)->with($this->eagerLoads);

        // Query khusus Low Stock untuk PDF
        $lowStockQuery = Product::where('seller_id', $sellerId)
                                ->with($this->eagerLoads)
                                ->where('stock', '<', 2)
                                ->join('categories', 'products.category_id', '=', 'categories.id')
                                ->orderBy('categories.name', 'asc') 
                                ->orderBy('products.name', 'asc')   
                                ->select('products.*'); 

        switch ($type) {
            case 'low_stock':
                $products = $lowStockQuery->get();
                $title = 'Laporan Daftar Produk Segera Dipesan';
                // Path View PDF untuk SRS-14
                $view_path = 'seller.reports.pdf.low_stock'; 
                break;
                
            case 'stock_by_quantity':
                $products = $baseQuery->withAvg('reviews', 'rating')
                                      ->orderBy('stock', 'desc')
                                      ->get();
                $title = 'Laporan Daftar Stok Produk Berdasarkan Kuantitas';
                // Path View PDF untuk SRS-12
                $view_path = 'seller.reports.pdf.stock_by_quantity'; 
                break;

            case 'stock_by_rating':
                // LOGIKA UNTUK SRS-MartPlace-13: Diurutkan berdasarkan Rating Menurun
                $products = $baseQuery->withAvg('reviews', 'rating')
                                      ->orderByDesc('reviews_avg_rating')
                                      ->get();
                $title = 'Laporan Daftar Produk Berdasarkan Rating';
                // Path View PDF untuk SRS-13
                $view_path = 'seller.reports.pdf.stock_by_rating'; 
                break;

            default:
                // Fallback jika type tidak valid, menggunakan path default
                $products = $baseQuery->get();
                $title = 'Laporan Umum Penjual';
                $view_path = 'seller.reports.pdf.low_stock';
                break;
        }

        // Siapkan data untuk View PDF
        $data = [
            'products' => $products,
            'title' => $title,
            'reportType' => $type,
            'seller' => Auth::guard('seller')->user(),
            'date' => now()->format('d-m-Y'), 
        ];
        
        // Panggil View PDF sesuai $view_path yang sudah ditentukan
        $pdf = Pdf::loadView($view_path, $data);
        
        $fileName = 'laporan_stok_' . $type . '_' . time() . '.pdf';
        return $pdf->download($fileName);
    }
}