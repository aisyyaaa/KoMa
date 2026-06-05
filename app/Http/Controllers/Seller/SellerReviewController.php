<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class SellerReviewController extends Controller
{
    public function index()
    {
        $sellerId = Auth::guard('seller')->id();

        $reviews = Review::with('product')
            ->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
            ->latest()
            ->paginate(15);

        return view('seller.reviews.index', compact('reviews'));
    }
}
