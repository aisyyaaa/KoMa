<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function activeSellers()
    {
        return view('admin.reports.active_sellers');
    }

    public function sellersByProvince()
    {
        return view('admin.reports.sellers_by_province');
    }

    public function productsByRating()
    {
        return view('admin.reports.products_by_rating');
    }
}
