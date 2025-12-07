<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;

class SellerProfileController extends Controller
{
    public function show(Seller $seller)
    {
        return view('seller.profile.show', compact('seller'));
    }

    public function edit(Seller $seller)
    {
        return view('seller.profile.edit', compact('seller'));
    }

    public function update(Request $request, Seller $seller)
    {
        // validate and update seller profile
        return redirect()->route('seller.profile.show', $seller);
    }
}
