<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class PlatformReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->paginate(20);
        return view('platform.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted');
    }
}
