<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;

class SellerProductController extends Controller
{
    public function index()
    {
        // Development: allow listing without auth
        $sellerId = auth()->id() ?? 1; // fallback to demo seller id
        
        // Get all categories for filter dropdown
        $categories = Category::all();
        
        // Build query with search and filter
        $query = Product::where('seller_id', $sellerId);
        
        // Search by product name
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }
        
        // Filter by category
        if (request('category')) {
            $query->where('category_id', request('category'));
        }
        
        // Paginate results
        $products = $query->paginate(10);
        
        return view('seller.products.index', ['products' => $products, 'categories' => $categories]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', ['categories' => $categories]);
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        // Handle upload gambar - note: form uses 'primary_images' but model expects 'primary_images'
        if ($request->hasFile('primary_images')) {
            $path = $request->file('primary_images')->store('products', 'public');
            $validated['primary_images'] = $path;
        }

        // Handle additional images (array of files)
        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $file->store('products', 'public');
                }
            }
            $validated['additional_images'] = $paths;
        }

        // Set seller_id (untuk dev: hardcode 1, nanti ganti auth()->id())
        $validated['seller_id'] = auth()->id() ?? 1;
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        // Buat produk baru
        Product::create($validated);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        return view('seller.products.show', ['product'=> $product]);
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        
        return view('seller.products.edit', ['product'=> $product, 'categories' => $categories]);
    }
    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Handle upload gambar baru - note: form uses 'primary_images'
        if ($request->hasFile('primary_images')) {
            $path = $request->file('primary_images')->store('products', 'public');
            $validated['primary_images'] = $path;
        }

        // Handle additional images on update: append to existing list
        if ($request->hasFile('additional_images')) {
            $existing = $product->additional_images ?? [];
            if (!is_array($existing)) {
                $existing = json_decode($existing, true) ?: [];
            }
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $existing[] = $file->store('products', 'public');
                }
            }
            $validated['additional_images'] = $existing;
        }

        if (isset($validated['name'])) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $product->id);
        }

        // Update produk
        $product->update($validated);

        return redirect()->route('seller.products.show', $product->id)
                        ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
