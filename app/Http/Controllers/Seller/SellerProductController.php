<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest; // Asumsi ProductRequest sudah ada & berisi validasi
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; // Wajib diimport

class SellerProductController extends Controller
{
    // Menggunakan construct untuk proteksi route Seller
    public function __construct()
    {
        // Asumsi guard 'seller' digunakan untuk otentikasi
        $this->middleware('auth:seller'); 
    }
    
    public function index()
    {
        $sellerId = Auth::guard('seller')->id(); 

        $categories = Category::all();
        
        // Membangun query dengan load relasi dan agregat
        $query = Product::where('seller_id', $sellerId)
                             ->withCount('reviews') // Tambahkan count review untuk tabel index
                             ->withAvg('reviews', 'rating'); // Tambahkan avg rating untuk tabel index
        
        // Search & Filter (tetap sama)
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
        
        // 1. Generate SLUG dari Nama Produk & Set seller_id
        $validated['slug'] = Str::slug($validated['name']);
        $validated['seller_id'] = Auth::guard('seller')->id(); 
        
        // 2. Handle upload gambar utama
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('products', 'public');
            $validated['primary_image'] = $path;
        }

        // 3. Handle gambar tambahan
        $additionalPaths = [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $additionalPaths[] = $file->store('products/additional', 'public');
                }
            }
        }
        // Jika tidak ada upload, set null array untuk cast di Model
        $validated['additional_images'] = $additionalPaths; 
        
        // 4. Set nilai default untuk kolom baru jika tidak ada input (walaupun validation sudah handle)
        $validated['base_shipping_cost'] = $validated['base_shipping_cost'] ?? 0;
        $validated['is_active'] = true;

        // 5. Buat produk baru
        Product::create($validated);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        // Autoritas Check
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Akses Ditolak.');
        }
        // Load reviews
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
        // Autoritas Check
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Akses Ditolak.');
        }
        $categories = Category::all();
        return view('seller.products.edit', ['product'=> $product, 'categories' => $categories]);
    }
    
    public function update(ProductRequest $request, Product $product)
    {
        // Autoritas Check
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Akses Ditolak.');
        }

        $validated = $request->validated();
        
        // 1. Generate SLUG untuk update
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // 2. Handle upload gambar utama baru (Jika ada)
        if ($request->hasFile('primary_image')) {
            // Hapus gambar lama
            if ($product->primary_image) {
                Storage::disk('public')->delete($product->primary_image);
            }
            $path = $request->file('primary_image')->store('products', 'public');
            $validated['primary_image'] = $path; // Key sesuai Model
        } else {
            // Jangan mengubah field jika tidak ada upload baru
            unset($validated['primary_image']);
        }

        // 3. Handle additional images (Hanya menambahkan path baru, tidak menghapus yang lama)
        $existingAdditionalPaths = $product->additional_images ?? []; 
        $newAdditionalPaths = [];
        
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $file) {
                if ($file && $file->isValid()) {
                    $newAdditionalPaths[] = $file->store('products/additional', 'public');
                }
            }
        }
        // Gabungkan path lama yang masih ada dan path baru
        $validated['additional_images'] = array_merge($existingAdditionalPaths, $newAdditionalPaths);
        
        // 4. Set nilai default untuk kolom baru jika tidak ada input
        $validated['base_shipping_cost'] = $validated['base_shipping_cost'] ?? 0;


        // 5. Update produk
        $product->update($validated);

        return redirect()->route('seller.products.detail', ['id' => $product->id])
             ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        // Autoritas Check
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Akses Ditolak.');
        }

        // Hapus file dari storage
        if ($product->primary_image) {
            Storage::disk('public')->delete($product->primary_image);
        }
        if (is_array($product->additional_images)) {
            foreach ($product->additional_images as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus');
    }
}