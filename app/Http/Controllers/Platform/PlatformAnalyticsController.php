<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlatformAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_platform_admin) {
                return redirect()->route('platform.auth.login');
            }
            return $next($request);
        });
    }
    public function productsPerCategory()
    {
        $rows = \App\Models\Category::leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select('categories.name', \DB::raw('count(products.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return response()->json([
            'labels' => $rows->pluck('name'),
            'data' => $rows->pluck('total'),
        ]);
    }

    public function sellersPerProvince()
    {
        $rows = \App\Models\Seller::select('pic_province', \DB::raw('count(*) as total'))
            ->groupBy('pic_province')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'labels' => $rows->pluck('pic_province'),
            'data' => $rows->pluck('total'),
        ]);
    }

    public function stats()
    {
        $total = \App\Models\Seller::count();
        $active = \App\Models\Seller::where('status', 'ACTIVE')->count();
        $inactive = \App\Models\Seller::where('status', 'REJECTED')->count();
        $commenters = \App\Models\Review::distinct('visitor_email')->count('visitor_email');

        return response()->json([
            'total_sellers' => $total,
            'active_sellers' => $active,
            'inactive_sellers' => $inactive,
            'commenters' => $commenters,
        ]);
    }
}
