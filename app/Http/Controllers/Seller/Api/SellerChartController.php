<?php

namespace App\Http\Controllers\Seller\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerChartController extends Controller
{
    public function stockPerProduct()
    {
        // return JSON data for product stock chart
        return response()->json([
            'labels' => ['Product A','Product B'],
            'data' => [10, 5],
        ]);
    }

    public function ratingPerProduct()
    {
        return response()->json([
            'labels' => ['Product A','Product B'],
            'data' => [4.5, 3.8],
        ]);
    }

    public function ratingByProvince()
    {
        return response()->json([
            'labels' => ['Province 1','Province 2'],
            'data' => [4.2, 3.9],
        ]);
    }
}
