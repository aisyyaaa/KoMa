<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class SellerReportController extends Controller
{
    public function stockByQuantity()
    {
        $sellerId = Auth::id() ?? 1; // Fallback for dev
        $products = Product::where('seller_id', $sellerId)
                            ->with(['category', 'reviews'])
                            ->orderBy('stock', 'desc')
                            ->paginate(15);

        return view('seller.reports.stock_by_quantity', compact('products'));
    }

    public function stockByRating()
    {
        $sellerId = Auth::id() ?? 1; // Fallback for dev
        $products = Product::where('seller_id', $sellerId)
                            ->with(['category', 'reviews'])
                            ->withCount('reviews')
                            ->leftJoin('reviews as r', 'products.id', '=', 'r.product_id')
                            ->selectRaw('products.*, avg(r.rating) as average_rating')
                            ->groupBy('products.id')
                            ->orderBy('average_rating', 'desc')
                            ->paginate(15);

        return view('seller.reports.stock_by_rating', compact('products'));
    }

    public function lowStock()
    {
        $sellerId = Auth::id() ?? 1; // Fallback for dev
        $products = Product::where('seller_id', $sellerId)
                            ->where('stock', '<', 2)
                            ->with(['category', 'reviews'])
                            ->orderBy('stock', 'asc')
                            ->paginate(15);

        return view('seller.reports.low_stock', compact('products'));
    }

    public function exportPdf($type)
    {
        // Ensure DOMPDF package is installed
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            // Provide a user-friendly redirect with flash message instead of aborting
            return redirect()->back()->with('error', 'PDF export requires package "barryvdh/laravel-dompdf". Please run: composer require barryvdh/laravel-dompdf');
        }

        $sellerId = Auth::id() ?? 1;

        switch ($type) {
            case 'stock_by_quantity':
                $products = Product::where('seller_id', $sellerId)
                            ->with(['category', 'reviews'])
                            ->orderBy('stock', 'desc')
                            ->get();
                $view = 'seller.reports.pdf.stock_by_quantity';
                $filename = 'laporan_stok_kuantitas.pdf';
                break;
            case 'stock_by_rating':
                $products = Product::where('seller_id', $sellerId)
                            ->with(['category', 'reviews'])
                            ->get()
                            ->sortByDesc(function($p) { return $p->averageRating(); });
                $view = 'seller.reports.pdf.stock_by_rating';
                $filename = 'laporan_stok_rating.pdf';
                break;
            case 'low_stock':
                $products = Product::where('seller_id', $sellerId)
                            ->where('stock', '<', 2)
                            ->with(['category', 'reviews'])
                            ->orderBy('stock', 'asc')
                            ->get();
                $view = 'seller.reports.pdf.low_stock';
                $filename = 'laporan_stok_rendah.pdf';
                break;
            default:
                abort(404);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, compact('products'));
        return $pdf->download($filename);
    }
}
