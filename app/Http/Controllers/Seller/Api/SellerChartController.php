<?php

namespace App\Http\Controllers\Seller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerChartController extends Controller
{
    public function stockPerProduct()
    {
        $sellerId = optional(Auth::user())->seller->id ?? 1;
        $products = \App\Models\Product::where('seller_id', $sellerId)
            ->orderByDesc('stock')
            ->limit(10)
            ->pluck('stock', 'name');

        $labels = $products->keys()->values();
        $data = $products->values()->map(function ($v) { return (int) $v; })->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function ratingPerProduct()
    {
        $sellerId = optional(Auth::user())->seller->id ?? 1;
        $products = \App\Models\Product::with('reviews')
            ->where('seller_id', $sellerId)
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->limit(10)
            ->get();

        $labels = $products->pluck('name')->values();
        $data = $products->map(function ($product) {
            $avg = $product->reviews->avg('rating');
            return $avg ? round($avg, 1) : 0;
        })->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function ratingByProvince()
    {
        $sellerId = optional(Auth::user())->seller->id ?? 1;
        $productIds = \App\Models\Product::where('seller_id', $sellerId)->pluck('id');

        $query = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->whereIn('reviews.product_id', $productIds)
            ->select('users.province', DB::raw('AVG(reviews.rating) as average_rating'))
            ->groupBy('users.province');

        $ratings = $query->pluck('average_rating', 'users.province');

        $labels = $ratings->keys()->values();
        $data = $ratings->values()->map(function ($v) { return round((float) $v, 1); })->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
