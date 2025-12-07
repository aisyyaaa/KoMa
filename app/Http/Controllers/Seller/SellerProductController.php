<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class SellerProductController extends Controller
{
    public function index()
    {
        return view('seller.products.index');
    }

    public function create()
    {
        return view('seller.products.create');
    }

    public function store(Request $request)
    {
        // validate and store product (use StoreProductRequest)
        return redirect()->route('seller.products.index');
    }

    public function show(Product $product)
    {
        return view('seller.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('seller.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // validate and update
        return redirect()->route('seller.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('seller.products.index');
    }
}
