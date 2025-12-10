<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlatformAnalyticsController extends Controller
{
    public function productsPerCategory()
    {
        // return JSON for charts
        return response()->json([
            'labels' => [],
            'data' => [],
        ]);
    }

    public function sellersPerProvince()
    {
        return response()->json([
            'labels' => [],
            'data' => [],
        ]);
    }

    public function stats()
    {
        return response()->json([
            'active_sellers' => 0,
            'inactive_sellers' => 0,
            'commenters' => 0,
        ]);
    }
}
