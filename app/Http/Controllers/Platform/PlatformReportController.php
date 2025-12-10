<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use Barryvdh\DomPDF\Facades\Pdf;
use Illuminate\Support\Facades\Log;

class PlatformReportController extends Controller
{
    public function activeSellers()
    {
        $active = Seller::where('status', 'ACTIVE')->count();
        $inactive = Seller::where('status', 'REJECTED')->count();
        return view('platform.reports.active_sellers', compact('active', 'inactive'));
    }

    public function sellersByProvince()
    {
        $byProvince = Seller::select('pic_province', \DB::raw('count(*) as total'))
            ->groupBy('pic_province')
            ->get();
        return view('platform.reports.sellers_by_province', compact('byProvince'));
    }

    public function productsByRating()
    {
        $products = Product::with('reviews')
            ->get()
            ->map(function ($p) {
                $p->avg_rating = $p->reviews->avg('rating') ?? 0;
                return $p;
            })
            ->sortByDesc('avg_rating');

        return view('platform.reports.products_by_rating', compact('products'));
    }

    /**
     * Export platform reports to PDF. Accepts types: seller-accounts, seller-locations, product-ratings
     */
    public function exportPdf($type)
    {
        try {
            if ($type === 'seller-accounts') {
                $active = Seller::where('status', 'ACTIVE')->count();
                $inactive = Seller::where('status', 'REJECTED')->count();
                $view = 'platform.reports.pdf.seller-accounts-pdf';
                $data = compact('active', 'inactive');
                $filename = 'seller-accounts.pdf';
            } elseif ($type === 'seller-locations') {
                $byProvince = Seller::select('pic_province', \DB::raw('count(*) as total'))->groupBy('pic_province')->get();
                $view = 'platform.reports.pdf.seller-locations-pdf';
                $data = compact('byProvince');
                $filename = 'seller-locations.pdf';
            } elseif ($type === 'product-ratings') {
                $products = Product::with('reviews', 'seller', 'category')
                    ->get()
                    ->map(function ($p) {
                        $p->avg_rating = round($p->reviews->avg('rating') ?? 0, 1);
                        return $p;
                    })
                    ->sortByDesc('avg_rating');
                $view = 'platform.reports.pdf.product-ratings-pdf';
                $data = compact('products');
                $filename = 'product-ratings.pdf';
            } else {
                return back()->withErrors(['type' => 'Unknown report type']);
            }

            if (!class_exists(Pdf::class)) {
                // dompdf not installed — show preview view instead
                return view(str_replace('.pdf.','preview.', $view), $data);
            }

            $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF export error: ' . $e->getMessage());
            return back()->withErrors(['pdf' => 'Terjadi kesalahan saat membuat PDF']);
        }
    }
}
