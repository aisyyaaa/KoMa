<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str; // PENTING: Tambahkan untuk generate slug

class SellerProductController extends Controller
{
    public function index()
    {
        // Mendapatkan ID penjual yang sedang login
        // Gunakan auth()->id() untuk lingkungan produksi, atau hardcode untuk dev/testing.
        $sellerId = auth()->id() ?? 1; // Default ke ID 1 jika belum login (untuk testing)

        // Dapatkan semua kategori untuk dropdown filter
        $categories = Category::all();
        
        // Membangun query
        $query = Product::where('seller_id', $sellerId);
        
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
        
        // FIX FATAL 1: Generate SLUG dari Nama Produk (Wajib, karena kolom NOT NULL)
        $validated['slug'] = Str::slug($validated['name']);

        // Handle upload gambar utama
        if ($request->hasFile('primary_images')) {
            $path = $request->file('primary_images')->store('products', 'public');
            $validated['primary_images'] = $path;
        }

        // Handle gambar tambahan
        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $file->store('products', 'public');
                }
            }
            // Pastikan additional_images disimpan sebagai JSON atau format array di DB
            $validated['additional_images'] = json_encode($paths); 
        } else {
             // Pastikan additional_images diset null/empty array jika tidak ada upload
             $validated['additional_images'] = json_encode([]);
        }

        // Set seller_id
        $validated['seller_id'] = auth()->id() ?? 1; // Menggunakan hardcode ID 1 untuk testing jika tidak ada Auth

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
        
        // FIX FATAL 2: Generate SLUG untuk update (jika nama berubah)
        // Kita perlu memastikan slug diupdate jika nama produk berubah
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle upload gambar utama baru
        if ($request->hasFile('primary_images')) {
            // OPTIONAL: Hapus gambar lama sebelum menyimpan yang baru
            // Storage::disk('public')->delete($product->primary_images); 
            $path = $request->file('primary_images')->store('products', 'public');
            $validated['primary_images'] = $path;
        }

        // Handle additional images on update: menggabungkan path baru dengan yang sudah ada
        if ($request->hasFile('additional_images')) {
            // Pastikan data yang ada sudah di-cast sebagai array di Model, jika tidak:
            $existingPaths = $product->additional_images ?? [];
            if (!is_array($existingPaths)) {
                 // Jika additional_images disimpan sebagai JSON string di DB
                $existingPaths = json_decode($existingPaths, true) ?: [];
            }
            
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $existingPaths[] = $file->store('products', 'public');
                }
            }
            $validated['additional_images'] = json_encode($existingPaths); 
        }

        // Update produk
        $product->update($validated);

        return redirect()->route('seller.products.show', $product->id)
                         ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        // OPTIONAL: Tambahkan logika untuk menghapus file gambar dari storage saat produk dihapus
        // $product->additional_images (loop dan hapus)
        // Storage::disk('public')->delete($product->primary_images);
        
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus');
    }
}