<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;

class SellerController extends Controller
{
    public function index()
    {
        return view('admin.sellers.index');
    }

    public function pending()
    {
        return view('admin.sellers.pending');
    }

    public function show(Seller $seller)
    {
        return view('admin.sellers.show', compact('seller'));
    }

    public function verify(Request $request, Seller $seller)
    {
        // placeholder: implement verification logic
        return redirect()->route('admin.sellers.show', $seller);
    }
}
