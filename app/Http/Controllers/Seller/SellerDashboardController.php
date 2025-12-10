<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class SellerDashboardController extends Controller
{
    public function index()
    {
        // prepare data for charts (stock, rating, by province)
        // Return the dashboard content view which extends the seller layout
        return view('seller.dashboard.index');
    }
}
