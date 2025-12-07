<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class SellerReviewController extends Controller
{
    public function index()
    {
        // list reviews for seller products
        return view('seller.reviews.index');
    }

    public function store(Request $request)
    {
        // store review (if review by seller for response)
        return back();
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back();
    }
}
