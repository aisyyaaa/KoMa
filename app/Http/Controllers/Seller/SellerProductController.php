<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage; 

class SellerProductController extends Controller
{
    // ... (index dan create methods tetap sama)

    public function index()
    {
        $sellerId = auth()->id() ?? 1; 

        $categories = Category::all();
        
        // Membangun query dengan load relasi dan agregat
        $query = Product::where('seller_id', $sellerId)
                         ->withCount('reviews') // Tambahkan count review untuk tabel index
                         ->withAvg('reviews', 'rating'); // Tambahkan avg rating untuk tabel index
        
        // Search & Filter
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }
        if (request('category')) {
            $query->where('category_id', request('category'));
        }
        
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
        
        // 1. Generate SLUG dari Nama Produk
        $validated['slug'] = Str::slug($validated['name']);

        // 2. Handle upload gambar utama
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('products', 'public');
            // Simpan ke kolom yang sesuai di DB (`primary_image`)
            $validated['primary_image'] = $path; 
        }

        // 3. Handle gambar tambahan
        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $file->store('products', 'public');
                }
            }
            // FIX KRITIS 2: Hapus json_encode(), Model akan mengurus cast ke array.
            $validated['additional_images'] = $paths; 
        } else {
            $validated['additional_images'] = null; 
        }

        // 4. Set seller_id
        $validated['seller_id'] = auth()->id() ?? 1; 
        
        // 5. Buat produk baru
        Product::create($validated);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        // Load reviews di halaman show untuk manajemen penjual
        $product->load(['category', 'reviews']); 
        
        return view('seller.products.show', ['product'=> $product]);
    }

    /**
     * Detail view that uses a fixed path `/seller/products/detail`.
     * Expects an `id` query parameter: /seller/products/detail?id=123
     */
    public function detail(Request $request)
    {
        $sellerId = auth()->id() ?? 1;
        $id = $request->query('id');
        $slug = $request->query('slug');

        // Try to resolve by id first, then fallback to slug.
        if ($id) {
            $product = Product::where('seller_id', $sellerId)->with(['category', 'reviews'])->find($id);
        } elseif ($slug) {
            $product = Product::where('seller_id', $sellerId)->where('slug', $slug)->with(['category', 'reviews'])->first();
        } else {
            abort(404);
        }

        if (!$product) {
            abort(404);
        }

        return view('seller.products.show', ['product' => $product]);
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('seller.products.edit', ['product'=> $product, 'categories' => $categories]);
    }
    
    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        
        // 1. Generate SLUG untuk update
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // 2. Handle upload gambar utama baru
        if ($request->hasFile('primary_image')) {
            // Hapus gambar lama
            if ($product->primary_image) {
                Storage::disk('public')->delete($product->primary_image); 
            }
            $path = $request->file('primary_image')->store('products', 'public');
            $validated['primary_image'] = $path; // Key sesuai Model
        }

        // 3. Handle additional images (Menambahkan path baru)
        if ($request->hasFile('additional_images')) {
            // Model cast otomatis me-load array, jadi kita tidak perlu decode
            $existingPaths = $product->additional_images ?? []; 
            
            // Pastikan $existingPaths adalah array (meskipun casts sudah ada)
            if (!is_array($existingPaths)) {
                 $existingPaths = json_decode($existingPaths, true) ?: [];
            }
            
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $existingPaths[] = $file->store('products', 'public');
                }
            }
            // FIX KRITIS 4: Hapus json_encode()
            $validated['additional_images'] = $existingPaths; 
        } else {
            // JANGAN hapus data lama jika tidak ada upload baru
            unset($validated['additional_images']);
        }

        // 4. Update produk
        $product->update($validated);

        return redirect()->route('seller.products.detail', ['id' => $product->id])
                 ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        // Menghapus gambar utama
        if ($product->primary_image) {
            Storage::disk('public')->delete($product->primary_image);
        }
        
        // Menghapus gambar tambahan
        if (is_array($product->additional_images)) {
            foreach ($product->additional_images as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus');
    }
}