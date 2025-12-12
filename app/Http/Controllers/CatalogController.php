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
        $searchQuery = $request->get('q');
        $categoryFilter = $request->get('category');
        $provinceFilter = $request->get('province');
        $cityFilter = $request->get('city');
        $districtFilter = $request->get('district');

        // 1. Inisialisasi Query Produk dengan Eager Loading dan Aggregates
        $query = Product::with(['seller', 'category'])
                         ->withAvg('reviews', 'rating')
                         ->withCount('reviews');

        // --- BATASAN KRITIS DINONAKTIFKAN SEMENTARA UNTUK DEBUGGING (SRS-MartPlace-04) ---
        
        // A. Wajib: Produk harus aktif (is_active = true)
        // DINONAKTIFKAN SEMENTARA untuk melihat apakah produk seeder muncul
        // $query->where('is_active', true); 

        // B. Wajib: Penjual produk tersebut harus berstatus ACTIVE
        // DINONAKTIFKAN SEMENTARA untuk melihat apakah produk seeder muncul
        // $query->whereHas('seller', function (Builder $q) {
        //     $q->where('status', 'ACTIVE');
        // });
        // --------------------------------------------------------------------------------

        // C. Pencarian Teks Bebas (Nama Produk, Nama Toko)
        if ($searchQuery) {
            $query->where(function (Builder $q) use ($searchQuery) {
                // Cari berdasarkan Nama Produk atau Deskripsi
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $searchQuery . '%')
                  // ATAU Cari berdasarkan Nama Toko
                  ->orWhereHas('seller', function (Builder $sq) use ($searchQuery) {
                      $sq->where('store_name', 'like', '%' . $searchQuery . '%');
                  });
            });
        }

        // D. Filter Kategori Produk
        if ($categoryFilter) {
            $query->whereHas('category', function (Builder $cq) use ($categoryFilter) {
                $cq->where('slug', $categoryFilter); 
            });
        }

        // E. Filter Lokasi Toko (SRS-MartPlace-05)
        if ($provinceFilter || $cityFilter || $districtFilter) {
            $query->whereHas('seller', function (Builder $sq) use ($provinceFilter, $cityFilter, $districtFilter) {
                
                if ($provinceFilter) { $sq->where('province', $provinceFilter); }
                if ($cityFilter) { $sq->where('city', $cityFilter); }
                if ($districtFilter) { $sq->where('district', $districtFilter); }
            });
        }
        
        // 2. Eksekusi Query
        $products = $query->orderBy('created_at', 'desc')
                          ->paginate(12)
                          ->withQueryString();

        // 3. Persiapkan Data Sidebar dan Kategori
        $categories = $this->getCategoriesWithIcons(); 
        $locations = $this->getUniqueSellerLocations(); 
        
        $activeFilters = $request->only(['q', 'category', 'province', 'city', 'district']);

        // 4. Kirim data ke view
        return view('katalog.index', compact('products', 'categories', 'locations', 'activeFilters'));
    }

    /**
     * Menampilkan detail satu produk tertentu.
     */
    public function show(Product $product)
    {
        $product->load([
            'seller', 
            'category', 
            'reviews' => function ($query) {
                // Mengganti with('user') ke latest() karena Review tidak punya user_id
                $query->latest(); 
            }
        ]);
        
        // Load Avg/Count secara eksplisit karena mungkin tidak di-eager load di 'index' sebelumnya
        $product->loadAvg('reviews', 'rating');
        $product->loadCount('reviews');
        
        return view('katalog.detail', compact('product'));
    }

    /**
     * Menangani permintaan AJAX untuk Autocomplete (Live Search).
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('term');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = [];
        $searchWildcard = '%' . $query . '%';

        // --- 1. Pencarian Produk dan Toko ---
        $products = Product::
            // where('is_active', true) // DINONAKTIFKAN
            // ->whereHas('seller', function(Builder $sq) {
            //     $sq->where('status', 'ACTIVE'); // DINONAKTIFKAN
            // })
            where(function (Builder $q) use ($searchWildcard) {
                $q->where('name', 'like', $searchWildcard)
                  ->orWhere('description', 'like', $searchWildcard);
            })
            ->with(['seller:id,store_name'])
            ->limit(10)
            ->get();
            
        foreach ($products as $product) {
            $results[] = [
                'type' => 'produk',
                'value' => $product->name . ' (Toko: ' . ($product->seller->store_name ?? 'N/A') . ')',
                'url' => route('katalog.show', $product->slug),
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
        
        // Query untuk Provinsi (Hanya dari penjual aktif)
        $provinceResults = Seller::select(DB::raw('DISTINCT province AS value'))
            ->where('province', 'like', $searchWildcard)
            ->where('status', 'ACTIVE') // BATASAN INI TETAP PENTING
            ->limit(3)
            ->get();
        foreach ($provinceResults as $loc) {
            if (!empty($loc->value)) {
                $results[] = ['type' => 'provinsi', 'value' => $loc->value . ' (Provinsi)', 'url' => route('katalog.index', ['province' => $loc->value])];
            }
        }
        
        // Query untuk Kabupaten/Kota (Hanya dari penjual aktif)
        $cityResults = Seller::select(DB::raw('DISTINCT city AS value'))
            ->where('city', 'like', $searchWildcard)
            ->where('status', 'ACTIVE') // BATASAN INI TETAP PENTING
            ->limit(3)
            ->get();
        foreach ($cityResults as $loc) {
            if (!empty($loc->value)) {
                $results[] = ['type' => 'kota', 'value' => $loc->value . ' (Kab/Kota)', 'url' => route('katalog.index', ['city' => $loc->value])];
            }
        }
        
        // Query untuk Kecamatan (Hanya dari penjual aktif)
        $districtResults = Seller::select(DB::raw('DISTINCT district AS value'))
            ->where('district', 'like', $searchWildcard)
            ->where('status', 'ACTIVE') // BATASAN INI TETAP PENTING
            ->limit(3)
            ->get();
        foreach ($districtResults as $loc) {
            if (!empty($loc->value)) {
                $results[] = ['type' => 'kecamatan', 'value' => $loc->value . ' (Kecamatan)', 'url' => route('katalog.index', ['district' => $loc->value])];
            }
        }

        $uniqueResults = collect($results)->unique('value')->take(10)->values();

        return response()->json($uniqueResults);
    }

    /**
     * Fungsi untuk mengambil kategori dengan atribut ikon dan warna.
     */
    private function getCategoriesWithIcons()
    {
        $categories = Category::all(['id', 'name', 'slug']);
        
        $categoryIcons = [
            'alat-tulis-kuliah' => ['color' => 'koma-accent', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.205 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.795 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.795 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.205 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>'], 
            'kebutuhan-kos' => ['color' => 'koma-primary', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'], 
            'buku-modul' => ['color' => 'koma-accent', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.205 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.795 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.795 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.205 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>'], 
            'aksesoris-gadget' => ['color' => 'koma-accent', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-.75M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>'],
            'makanan-minuman' => ['color' => 'koma-primary', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 00-7.072 0l-2.007 2.007c-.429.429-.429 1.125 0 1.554l7.072 7.072a5 5 0 007.072 0l2.007-2.007c.429-.429.429-1.125 0-1.554l-7.072-7.072zM5.536 18.464a5 5 0 007.072 0"></path></svg>']
        ];

        $categories->each(function($category) use ($categoryIcons) {
            $slug = $category->slug;
            $iconData = $categoryIcons[$slug] ?? ['color' => 'gray', 'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 1.105.895 2 2 2h12a2 2 0 002-2V7m-4 3L12 7l-4 3m4 0v10"></path></svg>'];
            
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
        $locations = Seller::where('status', 'ACTIVE') 
            ->distinct()
            ->get(['province', 'city', 'district']) 
            ->groupBy('province');

        $result = [];
        foreach ($locations as $provinceName => $cities) {
            $cityGroups = $cities->groupBy('city');
            $result[$provinceName] = [];
            foreach ($cityGroups as $cityName => $districts) {
                $result[$provinceName][$cityName] = $districts->pluck('district')->unique()->sort()->values()->toArray();
            }
        }

        return $result;
    }
}