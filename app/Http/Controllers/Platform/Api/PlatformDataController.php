<?php

namespace App\Http\Controllers\Platform\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;

class PlatformDataController extends Controller
{
    public function overview()
    {
        return response()->json([
            'total_sellers' => Seller::count(),
            'total_products' => Product::count(),
            'pending_sellers' => Seller::where('status','PENDING')->count(),
        ]);
    }
}
