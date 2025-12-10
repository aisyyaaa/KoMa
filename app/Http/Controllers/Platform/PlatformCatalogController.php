<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class PlatformCatalogController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('platform.catalog.index', compact('products'));
    }
}
