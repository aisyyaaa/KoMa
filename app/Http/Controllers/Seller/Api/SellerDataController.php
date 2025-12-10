<?php

namespace App\Http\Controllers\Seller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDataController extends Controller
{
    public function productsSummary()
    {
        $seller = Auth::user()->seller;
        if (!$seller) {
            return response()->json([
                'total_products' => 0,
                'active_products' => 0,
                'low_stock' => 0,
            ]);
        }
        $products = $seller->products;

        return response()->json([
            'total_products' => $products->count(),
            'low_stock' => $products->where('stock', '<', 10)->count(),
        ]);
    }

    public function reviewsSummary()
    {
        $seller = Auth::user()->seller;
        if (!$seller) {
            return response()->json([
                'total_reviews' => 0,
                'average_rating' => 0,
            ]);
        }
        $productIds = $seller->products()->pluck('id');

        $reviews = \App\Models\Review::whereIn('product_id', $productIds);

        return response()->json([
            'total_reviews' => $reviews->count(),
            'average_rating' => round($reviews->avg('rating'), 1),
        ]);
    }
}
