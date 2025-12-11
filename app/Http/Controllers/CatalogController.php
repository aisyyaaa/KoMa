<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; 
use App\Models\Seller;   
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB; 

class CatalogController extends Controller
{
    /**
     * Menampilkan daftar semua produk untuk pengunjung, dengan dukungan pencarian dan filter (SRS-MartPlace-04 & SRS-MartPlace-05).
     */
    public function index(Request $request)
    {
        // Mendapatkan semua parameter pencarian/filter dari URL
        $searchQuery = $request->get('q');            // Query Pencarian Teks
        $categoryFilter = $request->get('category');  // Filter Kategori (ID atau Slug)
        $provinceFilter = $request->get('province');  // Filter Provinsi
        $cityFilter = $request->get('city');          // Filter Kabupaten/Kota
        $districtFilter = $request->get('district');  // Filter Kecamatan

        // 1. Inisialisasi Query Produk dengan Eager Loading
        $query = Product::with(['seller', 'category', 'reviews']);

        // --- A. Pencarian Teks Bebas (Nama Produk, Nama Toko) ---
        if ($searchQuery) {
            $query->where(function (Builder $q) use ($searchQuery) {
                // Cari berdasarkan Nama Produk
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  // ATAU Cari berdasarkan Deskripsi Produk
                  ->orWhere('description', 'like', '%' . $searchQuery . '%')
                  // ATAU Cari berdasarkan Nama Toko (relasi Seller)
                  ->orWhereHas('seller', function (Builder $sq) use ($searchQuery) {
                      $sq->where('store_name', 'like', '%' . $searchQuery . '%');
                  });
            });
        }

        // --- B. Filter Kategori Produk ---
        if ($categoryFilter) {
            $query->whereHas('category', function (Builder $cq) use ($categoryFilter) {
                // Filter berdasarkan slug atau ID kategori
                $cq->where('slug', $categoryFilter)->orWhere('id', $categoryFilter);
            });
        }

        // --- C. Filter Lokasi Toko (Menggunakan Relasi Seller) ---
        $query->whereHas('seller', function (Builder $sq) use ($provinceFilter, $cityFilter, $districtFilter) {
            
            // Filter Provinsi
            if ($provinceFilter) {
                $sq->where('pic_province', $provinceFilter);
            }

            // Filter Kabupaten/Kota
            if ($cityFilter) {
                $sq->where('pic_city', $cityFilter);
            }
            
            // Filter Kecamatan
            if ($districtFilter) {
                $sq->where('pic_district', $districtFilter);
            }
        });

        // 2. Eksekusi Query
        $products = $query->orderBy('created_at', 'desc')
                          ->paginate(12)
                          ->withQueryString(); // Mempertahankan filter di link paginasi

        // 3. Persiapkan Data Sidebar dan Kategori
        // ⚠️ Menggunakan method baru untuk kategori
        $categories = $this->getCategoriesWithIcons(); 
        
        // Ambil daftar unik lokasi penjual (hanya dari penjual yang ACTIVE)
        $locations = $this->getUniqueSellerLocations(); 
        
        // Data filter yang aktif saat ini, untuk menandai di view
        $activeFilters = $request->only(['q', 'category', 'province', 'city', 'district']);


        // 4. Kirim data ke view
        return view('katalog.index', compact('products', 'categories', 'locations', 'activeFilters'));
    }

    /**
     * Menampilkan detail satu produk tertentu.
     */
    public function show(Product $product)
    {
        // Load data tambahan yang diperlukan di halaman detail:
        $product->load([
            'seller', 
            'category', 
            'reviews' => function ($query) {
                $query->latest(); // Urutkan komentar dari yang terbaru
            }
        ]);
        
        return view('katalog.detail', compact('product'));
    }

    /**
     * Menangani permintaan AJAX untuk Autocomplete (Live Search).
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('term'); // Ambil query dari AJAX

        if (empty($query)) {
            return response()->json([]);
        }

        $results = [];
        $searchWildcard = '%' . $query . '%';

        // --- 1. Pencarian Produk dan Toko ---
        $products = Product::where('name', 'like', $searchWildcard)
            ->orWhere('description', 'like', $searchWildcard)
            ->with(['seller:id,store_name']) // Hanya ambil nama toko
            ->limit(10)
            ->get();
            
        foreach ($products as $product) {
            $results[] = [
                'type' => 'produk',
                'value' => $product->name . ' (Toko: ' . ($product->seller->store_name ?? 'N/A') . ')',
                'url' => route('katalog.show', $product),
            ];
        }

        // --- 2. Pencarian Kategori ---
        $categories = Category::where('name', 'like', $searchWildcard)
            ->limit(3)
            ->get();
            
        foreach ($categories as $category) {
            $results[] = [
                'type' => 'kategori',
                'value' => 'Kategori: ' . $category->name,
                'url' => route('katalog.index', ['category' => $category->slug]),
            ];
        }
        
        // --- 3. Pencarian Lokasi ---
        
        // Query untuk Provinsi
        $provinceResults = Seller::select(DB::raw('DISTINCT pic_province AS value'))
            ->where('pic_province', 'like', $searchWildcard)
            ->limit(3)
            ->get();
        foreach ($provinceResults as $loc) {
            if (!empty($loc->value)) {
                $results[] = ['type' => 'provinsi', 'value' => $loc->value . ' (Provinsi)', 'url' => route('katalog.index', ['province' => $loc->value])];
            }
        }
        
        // Query untuk Kabupaten/Kota
        $cityResults = Seller::select(DB::raw('DISTINCT pic_city AS value'))
            ->where('pic_city', 'like', $searchWildcard)
            ->limit(3)
            ->get();
        foreach ($cityResults as $loc) {
            if (!empty($loc->value)) {
                $results[] = ['type' => 'kota', 'value' => $loc->value . ' (Kab/Kota)', 'url' => route('katalog.index', ['city' => $loc->value])];
            }
        }
        
        // Query untuk Kecamatan
        $districtResults = Seller::select(DB::raw('DISTINCT pic_district AS value'))
            ->where('pic_district', 'like', $searchWildcard)
            ->limit(3)
            ->get();
        foreach ($districtResults as $loc) {
            if (!empty($loc->value)) {
                $results[] = ['type' => 'kecamatan', 'value' => $loc->value . ' (Kecamatan)', 'url' => route('katalog.index', ['district' => $loc->value])];
            }
        }

        // Gabungkan dan pastikan hasilnya unik (berdasarkan value, untuk menghindari duplikasi)
        $uniqueResults = collect($results)->unique('value')->take(10)->values();

        return response()->json($uniqueResults);
    }

    /**
     * Fungsi untuk mengambil kategori dengan atribut ikon dan warna (dipindahkan dari Blade ke Controller).
     */
    private function getCategoriesWithIcons()
    {
        $categories = Category::all(['id', 'name', 'slug']);

        // Data Kategori DUMMY/ICON
        $categoryIcons = [
            'alat-tulis-kuliah' => ['color' => 'koma-accent', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.205 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.795 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.795 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.205 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>'], 
            'kebutuhan-kos' => ['color' => 'koma-primary', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'], 
            'buku-modul' => ['color' => 'koma-accent', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.205 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.795 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.795 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.205 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>'], 
            'aksesoris-gadget' => ['color' => 'koma-accent', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-75-.75M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>'],
            'makanan-minuman' => ['color' => 'koma-primary', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 00-7.072 0l-2.007 2.007c-.429.429-.429 1.125 0 1.554l7.072 7.072a5 5 0 007.072 0l2.007-2.007c.429-.429.429-1.125 0-1.554l-7.072-7.072zM5.536 18.464a5 5 0 007.072 0"></path></svg>']
        ];

        // Map kategori untuk menambahkan properti ikon dan warna
        $categories->each(function($category) use ($categoryIcons) {
            $slug = $category->slug;
            $iconData = $categoryIcons[$slug] ?? ['color' => 'gray', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 1.105.895 2 2 2h12a2 2 0 002-2V7m-4 3L12 7l-4 3m4 0v10"></path></svg>'];
            
            // Menambahkan properti ke Model Eloquent secara dinamis
            $category->color = $iconData['color'];
            $category->icon_svg = $iconData['icon_svg'];
        });

        return $categories;
    }

    /**
     * Mengambil daftar unik lokasi (Provinsi, Kota, Kecamatan) dari penjual aktif.
     */
    private function getUniqueSellerLocations()
    {
        // Ambil data lokasi dari Seller yang berstatus ACTIVE
        $locations = Seller::where('status', 'ACTIVE') 
            ->distinct()
            ->get(['pic_province', 'pic_city', 'pic_district'])
            ->groupBy('pic_province');

        $result = [];
        // Mengubah format agar mudah diakses di View: 
        // [Provinsi] => [Kota] => [Daftar Kecamatan]
        foreach ($locations as $provinceName => $cities) {
            $cityGroups = $cities->groupBy('pic_city');
            $result[$provinceName] = [];
            foreach ($cityGroups as $cityName => $districts) {
                $result[$provinceName][$cityName] = $districts->pluck('pic_district')->unique()->sort()->values()->toArray();
            }
        }

        return $result;
    }
}