<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class SellerDashboardController extends Controller
{
    public function index()
    {
        // prepare data for charts (stock, rating, by province)
        return view('seller.dashboard.index');
    }
}
