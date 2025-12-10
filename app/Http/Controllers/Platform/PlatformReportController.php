<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PlatformReportController extends Controller
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
    public function activeSellers()
    {
        // get counts
        $active = Seller::where('status', 'ACTIVE')->count();
        $inactive = Seller::where('status', 'REJECTED')->count();
        $total = $active + $inactive;

        // fetch all sellers with relevant fields for the report
        $sellers = Seller::select('id', 'store_name', 'pic_name', 'pic_email', 'pic_phone', 'status', 'updated_at')
            ->orderBy('store_name')
            ->get()
            ->map(function ($s) {
                // Assumption: if seller status is not ACTIVE, treat last activity as 1 month ago
                if (strtoupper($s->status) !== 'ACTIVE') {
                    $s->last_active = now()->subMonth();
                } else {
                    // otherwise use updated_at as proxy for last activity (sales/orders not available)
                    $s->last_active = $s->updated_at;
                }
                return $s;
            });

        return view('platform.reports.active_sellers', compact('active', 'inactive', 'total', 'sellers'));
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
        // Use Eloquent withAvg to compute average rating and paginate the results
        $products = Product::with(['reviews', 'seller', 'category'])
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->paginate(15);

        // Normalize attribute name to match view expectation
        $products->getCollection()->transform(function ($p) {
            $p->avg_rating = round($p->reviews_avg_rating ?? 0, 1);
            return $p;
        });

        return view('platform.reports.products_by_rating', compact('products'));
    }

    /**
     * Export platform reports to PDF. Accepts types: seller-accounts, seller-locations, product-ratings
     */
    public function exportPdf($type)
    {
        try {
            Log::info('PlatformReportController::exportPdf called', ['type' => $type, 'user_id' => auth()->id()]);
            if ($type === 'seller-accounts') {
                $active = Seller::where('status', 'ACTIVE')->count();
                $inactive = Seller::where('status', 'REJECTED')->count();

                // include full seller list for the PDF
                $sellers = Seller::select('store_name', 'pic_name', 'pic_email', 'pic_phone', 'status', 'updated_at')
                    ->orderBy('store_name')
                    ->get()
                    ->map(function ($s) {
                        if (strtoupper($s->status) !== 'ACTIVE') {
                            $s->last_active = now()->subMonth();
                        } else {
                            $s->last_active = $s->updated_at;
                        }
                        return $s;
                    });

                $view = 'platform.reports.pdf.seller-accounts-pdf';
                $data = compact('active', 'inactive', 'sellers');
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

            $pdfAvailable = class_exists(Pdf::class);
            Log::info('PDF class available?', ['available' => $pdfAvailable]);
            if (!$pdfAvailable) {
                // dompdf not installed — instruct user to install the package
                Log::warning('DomPDF not available, redirecting back with install message', ['view' => $view]);
                return redirect()->back()->with('error', 'PDF export requires package "barryvdh/laravel-dompdf". Please run: composer require barryvdh/laravel-dompdf');
            }

            $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'portrait');
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF export error: ' . $e->getMessage());
            return back()->withErrors(['pdf' => 'Terjadi kesalahan saat membuat PDF']);
        }
    }
}
