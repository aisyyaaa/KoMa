<?php

namespace App\Http\Controllers\Seller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerDataController extends Controller
{
    public function productsSummary()
    {
        return response()->json([
            'total_products' => 12,
            'active_products' => 10,
            'low_stock' => 3,
        ]);
    }

    public function reviewsSummary()
    {
        return response()->json([
            'total_reviews' => 45,
            'average_rating' => 4.2,
        ]);
    }
}
