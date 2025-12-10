<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $sellerId = optional(Auth::user())->seller->id ?? 1;
        $products = Product::where('seller_id', $sellerId)->withCount('reviews')->get();
        $productIds = $products->pluck('id');
        $reviews = Review::whereIn('product_id', $productIds);

        $stats = [
            'total_products' => $products->count(),
            'low_stock' => $products->where('stock', '<', 10)->count(),
            'average_rating' => round((float) $reviews->avg('rating'), 1),
            'total_reviews' => $reviews->count(),
        ];

        return view('seller.dashboard.index', compact('stats'));
    }
}
