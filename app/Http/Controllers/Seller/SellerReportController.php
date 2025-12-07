<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class SellerReportController extends Controller
{
    public function stockByQuantity()
    {
        return view('seller.reports.stock_by_quantity');
    }

    public function stockByRating()
    {
        return view('seller.reports.stock_by_rating');
    }

    public function lowStock()
    {
        return view('seller.reports.low_stock');
    }

    public function exportPdf($type)
    {
        // export report PDF placeholder
        return response()->download(storage_path('app/public/dummy.pdf'));
    }
}
