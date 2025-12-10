<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;

class PlatformSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Seller::query();

        // Optional: Filter by status
        if ($request->has('status') && in_array($request->status, ['ACTIVE', 'PENDING', 'REJECTED'])) {
            $query->where('status', $request->status);
        }

        $sellers = $query->latest()->paginate(15)->withQueryString();

        return view('platform.sellers.index', compact('sellers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        // Eager load products and their reviews for efficiency
        $seller->load(['products' => function ($query) {
            $query->withCount('reviews')->latest();
        }]);

        return view('platform.sellers.show', compact('seller'));
    }
}
