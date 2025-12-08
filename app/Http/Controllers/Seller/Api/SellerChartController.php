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
        $user = Auth::user();

        if ($user && $user->seller) {
            $products = $user->seller->products()->pluck('stock', 'name');
        } else {
            // fallback: top 10 products by stock across platform
            $products = \App\Models\Product::orderBy('stock', 'desc')->limit(10)->pluck('stock', 'name');
        }

        $labels = $products->keys()->values();
        $data = $products->values()->map(function ($v) { return (int) $v; })->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function ratingPerProduct()
    {
        $user = Auth::user();

        if ($user && $user->seller) {
            $products = $user->seller->products()->with('reviews')->get();
        } else {
            // fallback: top 10 products by total reviews across platform
            $products = \App\Models\Product::with('reviews')
                ->withCount('reviews')
                ->orderBy('reviews_count', 'desc')
                ->limit(10)
                ->get();
        }

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
        $user = Auth::user();

        if ($user && $user->seller) {
            $productIds = $user->seller->products()->pluck('id');
            $query = DB::table('reviews')
                ->join('users', 'reviews.user_id', '=', 'users.id')
                ->whereIn('reviews.product_id', $productIds)
                ->select('users.province', DB::raw('AVG(reviews.rating) as average_rating'))
                ->groupBy('users.province');
        } else {
            // fallback: platform-wide by province
            $query = DB::table('reviews')
                ->join('users', 'reviews.user_id', '=', 'users.id')
                ->select('users.province', DB::raw('AVG(reviews.rating) as average_rating'))
                ->groupBy('users.province');
        }

        $ratings = $query->pluck('average_rating', 'users.province');

        $labels = $ratings->keys()->values();
        $data = $ratings->values()->map(function ($v) { return round((float) $v, 1); })->values();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
