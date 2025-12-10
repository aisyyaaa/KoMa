<?php

namespace App\Http\Controllers\Seller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDataController extends Controller
{
    public function productsSummary()
    {
        $sellerId = optional(Auth::user())->seller->id ?? 1;
        $products = \App\Models\Product::where('seller_id', $sellerId)->get();

        return response()->json([
            'total_products' => $products->count(),
            'low_stock' => $products->where('stock', '<', 10)->count(),
        ]);
    }

    public function reviewsSummary()
    {
        $sellerId = optional(Auth::user())->seller->id ?? 1;
        $productIds = \App\Models\Product::where('seller_id', $sellerId)->pluck('id');

        $reviews = \App\Models\Review::whereIn('product_id', $productIds);

        return response()->json([
            'total_reviews' => $reviews->count(),
            'average_rating' => round($reviews->avg('rating'), 1),
        ]);
    }
}
