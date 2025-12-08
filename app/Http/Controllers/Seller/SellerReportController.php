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
        // export report PDF placeholder
        return response()->download(storage_path('app/public/dummy.pdf'));
    }
}
