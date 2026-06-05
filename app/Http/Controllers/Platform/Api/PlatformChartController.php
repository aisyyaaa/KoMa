<?php

namespace App\Http\Controllers\Platform\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;

class PlatformChartController extends Controller
{
    public function productsPerCategory()
    {
        // Use Category with products_count to avoid complex joins
        $rows = \App\Models\Category::withCount('products')->get();

        $labels = $rows->pluck('name')->toArray();
        $data = $rows->pluck('products_count')->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function sellersPerProvince()
    {
        $data = Seller::select('pic_province', DB::raw('COUNT(*) as total'))
            ->groupBy('pic_province')
            ->get();

        $labels = $data->pluck('pic_province')->values()->toArray();
        $values = $data->pluck('total')->values()->toArray();

        return response()->json([
            'labels' => $labels,
            'data' => $values,
        ]);
    }

    public function productsByRating()
    {
        $products = Product::with('reviews', 'seller', 'category')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'rating' => round($p->reviews->avg('rating') ?? 0, 1),
                    'seller' => $p->seller->store_name ?? null,
                    'category' => $p->category->name ?? null,
                    'price' => $p->price ?? null,
                    'province' => $p->seller->pic_province ?? null,
                ];
            })
            ->sortByDesc('rating')
            ->values();

        return response()->json([ 'products' => $products ]);
    }
}
