<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KoMa Market - Katalog Produk</title>
    {{-- Pastikan resources/css/app.css di-compile menggunakan npm run dev/prod --}}
    @vite('resources/css/app.css') 
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-white text-koma-text-dark">

    @php
        $showProfileAfterRegister = session('is_registered_flag');
    @endphp

    {{-- HEADER & NAVIGATION BAR --}}
    <header class="shadow-md">
        
        {{-- BARIS ATAS KOSONGAN (Top Bar Warna Solid) --}}
        <div class="bg-koma-accent w-full py-2">
            <div class="container mx-auto px-4"></div>
        </div>

        {{-- Baris Utama Logo & Search --}}
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            
            {{-- LOGO SEBAGAI LINK KE KATALOG --}}
            <a href="{{ route('katalog.index') }}" class="text-2xl font-bold text-koma-primary hover:text-koma-danger transition duration-150">
                KoMa Market
            </a>
            
            <div class="flex-1 max-w-xl mx-8">
                {{-- Form Pencarian Global (SRS-MartPlace-05) --}}
                <form action="{{ route('katalog.index') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Cari produk atau toko..." class="w-full p-2 border-2 border-koma-primary rounded-lg focus:outline-none focus:border-koma-accent" value="{{ request('q') }}">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 bg-koma-primary text-white rounded-r-lg hover:bg-koma-danger">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    
                    {{-- Tambahkan input tersembunyi untuk mempertahankan filter lain saat melakukan pencarian teks --}}
                    @foreach (['category', 'province', 'city', 'district'] as $filterKey)
                        @if (request($filterKey))
                            <input type="hidden" name="{{ $filterKey }}" value="{{ request($filterKey) }}">
                        @endif
                    @endforeach
                </form>
            </div>

            {{-- CONTAINER UNTUK LOGIN/PROFIL --}}
            <div id="auth-container" class="flex items-center text-koma-text-dark">
                
                <div id="login-button" class="{{ auth()->check() ? 'hidden' : '' }}">
                    <a href="{{ route('login') }}" class="text-white bg-koma-accent hover:bg-koma-hover-light hover:text-koma-text-dark font-medium border border-koma-accent rounded-md px-3 py-2 transition duration-150">
                        Login
                    </a>
                </div>

                <div id="profile-icon" class="{{ auth()->check() ? '' : 'hidden' }}">
                    <a href="/seller/dashboard" class="hover:text-koma-primary flex items-center space-x-2 p-2 rounded-full bg-koma-bg-light">
                        <svg class="w-6 h-6 text-koma-primary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08s5.97 1.09 6 3.08c-1.29 1.94-3.5 3.22-6 3.22z"></path></svg>
                        <span class="font-medium text-sm hidden sm:inline text-koma-primary">Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="container mx-auto px-4 mt-6">
        
        {{-- 1. PROMOTIONAL BANNER --}}
        <div class="py-6"> 
            <div class="bg-koma-bg-light p-8 rounded-xl shadow-lg flex items-center justify-between overflow-hidden relative">
                <div>
                    <span class="text-sm text-koma-text-dark font-medium">Spesial Mahasiswa</span>
                    <h2 class="text-4xl font-extrabold mt-2 leading-tight text-koma-primary">Diskon 20% Untuk Produk KoMa</h2>
                    <p class="mt-3 text-lg text-koma-text-dark">Kumpulkan Koin KoMa Anda Sekarang!</p>
                </div>
                
                <div class="hidden md:block w-1/3 h-full absolute right-0 top-0">
                    <img src="https://via.placeholder.com/350x200/cad7fa/5d5e62?text=Promo+Diskon" alt="Promo Diskon" class="w-full h-full object-cover rounded-r-xl opacity-70">
                </div>
            </div>
        </div>

        {{-- ⚠️ KATEGORI BARIS ATAS (PERBAIKAN KONSISTENSI & IKON) --}}
        <div class="py-6"> 
            <h2 class="text-2xl font-bold border-b pb-2 mb-6 text-koma-text-dark">Jelajahi Kategori Pilihan</h2>
            
            <div class="flex space-x-4 overflow-x-auto pb-4 scrollbar-hide">
                
                @php
                    // Data Kategori DUMMY/ICON (Menggunakan slug sebagai key)
                    // Data ini sudah dimap ke Model $category di Controller (method getCategoriesWithIcons)
                @endphp

                @foreach ($categories as $category)
                    @php
                        // Membuat link filter yang mempertahankan query lain, hanya mengubah kategori
                        $categoryLink = route('katalog.index', array_merge(request()->query(), ['category' => $category->slug, 'page' => 1]));
                        $isActive = ($activeFilters['category'] ?? null) === $category->slug;
                    @endphp

                    <a href="{{ $categoryLink }}" 
                        {{-- ⚠️ Class flex-shrink-0 DITAMBAHKAN untuk menjamin lebar w-32 --}}
                        class="flex-shrink-0 w-32 h-36 flex flex-col items-center justify-center p-3 rounded-xl border {{ $isActive ? 'border-koma-primary shadow-xl bg-koma-hover-light' : 'border-koma-bg-light shadow-md' }} hover:shadow-xl hover:bg-koma-hover-light transition duration-150 group">
                        
                        {{-- Icon Box: Warna dinamis dari $category->color --}}
                        <div class="w-16 h-16 bg-koma-bg-light rounded-xl flex items-center justify-center mb-3 group-hover:bg-{{ $category->color }} transition duration-150 text-{{ $category->color }} group-hover:text-white">
                            {!! $category->icon_svg !!}
                        </div>
                        
                        <span class="text-sm font-medium text-center text-koma-text-dark">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 3. LAYOUT UTAMA DUA KOLOM (SIDEBAR & PRODUK) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6 pt-6">
            
            {{-- SIDEBAR FILTER --}}
            <aside class="col-span-1">
                <h3 class="text-xl font-bold text-koma-primary mb-4 border-b pb-2">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Detail
                </h3>
                
                <form action="{{ route('katalog.index') }}" method="GET" class="space-y-6">
                    
                    {{-- Hidden Input untuk mempertahankan Search Query (q) dan Kategori (category) --}}
                    @if ($activeFilters['q'] ?? false)
                        <input type="hidden" name="q" value="{{ $activeFilters['q'] }}">
                    @endif
                    @if ($activeFilters['category'] ?? false)
                        <input type="hidden" name="category" value="{{ $activeFilters['category'] }}">
                    @endif
                    
                    {{-- 2. Filter Lokasi Toko (NON-SEQUENTIAL/INDEPENDENT) --}}
                    <div class="border p-4 rounded-lg bg-gray-50">
                        <h4 class="font-semibold mb-3 text-koma-text-dark">Lokasi Toko</h4>
                        
                        {{-- Filter Provinsi --}}
                        <div class="mb-3">
                            <label for="province" class="block text-xs font-medium text-gray-700 mb-1">Provinsi</label>
                            <select name="province" id="province" class="w-full p-2 border rounded-md focus:ring-koma-primary focus:border-koma-primary text-sm" onchange="this.form.submit()">
                                <option value="">Semua Provinsi</option>
                                @foreach (array_keys($locations) as $provinceName)
                                    <option value="{{ $provinceName }}" @selected(($activeFilters['province'] ?? null) === $provinceName)>
                                        {{ $provinceName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Kabupaten/Kota (INDEPENDENT) --}}
                        <div class="mb-3">
                            <label for="city" class="block text-xs font-medium text-gray-700 mb-1">Kabupaten/Kota</label>
                            <select name="city" id="city" class="w-full p-2 border rounded-md focus:ring-koma-primary focus:border-koma-primary text-sm" onchange="this.form.submit()">
                                <option value="">Semua Kota/Kabupaten</option>
                                
                                @php
                                    // Ambil semua kota unik dari seluruh data penjual
                                    $allCities = collect($locations)->flatMap(function ($cities) {
                                        return array_keys($cities);
                                    })->unique()->sort();
                                @endphp

                                @foreach ($allCities as $cityName)
                                    <option value="{{ $cityName }}" @selected(($activeFilters['city'] ?? null) === $cityName)>
                                        {{ $cityName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Filter Kecamatan (INDEPENDENT) --}}
                        <div class="mb-3">
                            <label for="district" class="block text-xs font-medium text-gray-700 mb-1">Kecamatan</label>
                            <select name="district" id="district" class="w-full p-2 border rounded-md focus:ring-koma-primary focus:border-koma-primary text-sm" onchange="this.form.submit()">
                                <option value="">Semua Kecamatan</option>
                                
                                @php
                                    // Ambil semua kecamatan unik dari seluruh data penjual
                                    $allDistricts = collect($locations)->flatMap(function ($cities) {
                                        return collect($cities)->flatten();
                                    })->unique()->sort();
                                @endphp

                                @foreach ($allDistricts as $districtName)
                                    <option value="{{ $districtName }}" @selected(($activeFilters['district'] ?? null) === $districtName)>
                                        {{ $districtName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            @if (($activeFilters['province'] ?? false) || ($activeFilters['category'] ?? false) || ($activeFilters['city'] ?? false) || ($activeFilters['district'] ?? false))
                                <a href="{{ route('katalog.index', ['q' => $activeFilters['q'] ?? '']) }}" class="w-full inline-block text-center text-sm font-medium text-koma-danger hover:text-koma-primary">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Hapus Semua Filter
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                
            </aside>

            {{-- DAFTAR PRODUK (Kolom 3/4) --}}
            <div class="md:col-span-3 lg:col-span-3">
                
                {{-- JUDUL UTAMA DINAMIS --}}
                @php
                    // Cek apakah ada filter atau query yang aktif selain paginasi
                    $isFiltering = !empty(array_filter(request()->except(['page'])));
                @endphp

                <h2 class="text-2xl font-bold border-b pb-2 mb-6 text-koma-text-dark">
                    @if ($isFiltering)
                        Hasil Pencarian 
                        <span class="text-lg font-normal text-gray-500">
                            @if ($activeFilters['q'] ?? false)
                            untuk "{{ $activeFilters['q'] }}"
                            @endif
                            ({{ $products->total() }} Produk Ditemukan)
                        </span>
                    @else
                        Produk Terbaru
                    @endif
                </h2>
                
                {{-- Grid produk dengan penyesuaian untuk menghindari himpitan --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4"> 
                    @forelse ($products as $product)
                        <div class="bg-white border border-koma-bg-light rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                            
                            {{-- Gambar dan Link ke Detail Produk --}}
                            <a href="{{ route('katalog.show', $product) }}" class="block h-52 overflow-hidden">
                                <img 
                                    src="{{ $product->primary_image_url ?? 'https://via.placeholder.com/300x200/dde0e7/5d5e62?text=No+Image' }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-cover hover:scale-105 transition duration-300"
                                >
                            </a>
                            
                            <div class="p-3">
                                {{-- Nama Produk --}}
                                <h3 class="text-md font-semibold text-koma-text-dark truncate">
                                    <a href="{{ route('katalog.show', $product) }}" class="hover:text-koma-primary">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                
                                {{-- Harga (Diskon/Normal) --}}
                                @if ($product->discount_price > 0 && $product->discount_price < $product->price)
                                    <p class="text-lg font-bold text-koma-danger mt-1">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-lg font-bold text-koma-primary mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @endif

                                {{-- Rating Rata-rata (SRS-MartPlace-04) --}}
                                <div class="flex items-center mt-1">
                                    @php
                                        $avgRating = $product->average_rating; // Perbaikan: Akses sebagai properti
                                        $rating = round($avgRating);
                                    @endphp
                                    {{-- Tampilkan Bintang --}}
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.02l6.56-.955L10 0l2.953 6.065 6.56.955-4.758 4.525 1.123 6.545z"></path></svg>
                                    @endfor
                                    <span class="ml-1 text-xs text-gray-500">({{ number_format($avgRating, 1) }})</span>
                                </div>
                                
                                {{-- Nama Toko --}}
                                <p class="text-xs text-gray-500 mt-1">
                                    Toko: {{ $product->seller->store_name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-gray-100 rounded-xl">
                            <p class="text-xl text-gray-600">
                                Maaf, tidak ada produk yang ditemukan.
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- Paginasi --}}
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
        
    </main>
    
    {{-- FOOTER --}}
    <footer class="mt-10 py-6 bg-koma-accent text-white text-center text-sm">
        &copy; 2025 KoMa Market. Koperasi Mahasiswa. All Rights Reserved.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. Logika Otentikasi (Tetap Sama) ---
            const loginButton = document.getElementById('login-button');
            const profileIcon = document.getElementById('profile-icon');
            
            const isLoggedIn = '{{ auth()->check() ? "1" : "0" }}' === '1';
            const isLoggedInSession = sessionStorage.getItem('is_seller_logged_in') === 'true';

            let showProfile = isLoggedInSession || isLoggedIn;

            if (isLoggedIn) {
                sessionStorage.setItem('is_seller_logged_in', 'true');
                showProfile = true;
            }

            if (showProfile) {
                loginButton.classList.add('hidden');
                profileIcon.classList.remove('hidden');
            } else {
                loginButton.classList.remove('hidden');
                profileIcon.classList.add('hidden');
            }
            
            // --- 2. LIVE SEARCH / AUTOCOMPLETE (SRS-MartPlace-05) ---

            const searchInput = document.querySelector('input[name="q"]');
            const searchForm = searchInput.closest('form');
            let autocompleteList; // Variabel untuk menyimpan elemen daftar saran
            let debounceTimeout;

            // Fungsi Debouncing: Menunda eksekusi AJAX sampai user berhenti mengetik (300ms)
            const debounce = (func, delay) => {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(func, delay);
            };

            // Fungsi untuk membuat dan menampilkan daftar autocomplete
            const showAutocomplete = (results) => {
                // Hapus daftar lama jika ada
                if (autocompleteList) {
                    autocompleteList.remove();
                }

                if (results.length === 0) return;

                // 1. Buat kontainer untuk daftar (UL)
                autocompleteList = document.createElement('ul');
                // Styling agar daftar muncul di atas konten lain, lebar penuh, dan scrollable
                autocompleteList.className = 'absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-xl mt-0.5 max-h-60 overflow-y-auto';
                
                // 2. Isi daftar dengan hasil
                results.forEach(item => {
                    const listItem = document.createElement('li');
                    // Styling item
                    listItem.className = 'px-4 py-2 cursor-pointer hover:bg-gray-100 flex justify-between items-center text-sm border-b border-gray-100';

                    // Teks hasil
                    const resultText = document.createElement('span');
                    resultText.textContent = item.value;
                    listItem.appendChild(resultText);

                    // Tipe hasil (Kategori, Toko, Produk, Lokasi)
                    const resultType = document.createElement('span');
                    resultType.textContent = item.type.toUpperCase();
                    resultType.className = 'text-xs font-medium text-koma-accent bg-koma-bg-light px-2 py-0.5 rounded-full';
                    listItem.appendChild(resultType);
                    
                    // 3. Tambahkan Event Listener saat item diklik
                    listItem.addEventListener('click', () => {
                        // Langsung navigasi ke URL filter/detail yang sudah disiapkan Controller
                        window.location.href = item.url;
                    });
                    
                    autocompleteList.appendChild(listItem);
                });

                // 4. Masukkan daftar di bawah input pencarian
                // Kita perlu menyisipkan daftar tepat di bawah input, di dalam elemen form.
                // Gunakan insertAdjacentElement untuk penempatan yang tepat
                searchInput.insertAdjacentElement('afterend', autocompleteList);
            };

            // Event Listener untuk setiap ketikan di input pencarian
            searchInput.addEventListener('input', function() {
                const term = this.value;

                // Tampilkan saran hanya jika query minimal 1 karakter
                if (term.length < 1) { 
                    if (autocompleteList) autocompleteList.remove();
                    return;
                }

                // Gunakan Debounce (AJAX)
                debounce(() => {
                    // Panggil rute autocomplete yang kita buat di Controller
                    // ⚠️ Pastikan route name ini BENAR
                    fetch(`{{ route('katalog.autocomplete') }}?term=${term}`)
                        .then(response => {
                            if (!response.ok) {
                                // Jika ada error HTTP (404, 500, dll), kita harus tahu!
                                console.error('HTTP Error:', response.status, response.statusText);
                                return response.text().then(text => { throw new Error(text) });
                            }
                            return response.json();
                        })
                        .then(data => {
                            showAutocomplete(data);
                        })
                        .catch(error => {
                            console.error('Error fetching autocomplete data:', error);
                            if (autocompleteList) autocompleteList.remove();
                        });
                }, 300); // Waktu tunda 300ms
            });

            // Sembunyikan daftar jika mengklik di luar area form
            document.addEventListener('click', function(event) {
                if (autocompleteList && !searchForm.contains(event.target)) {
                    autocompleteList.remove();
                }
            });
            
            // Hapus daftar autocomplete jika form disubmit biasa
            searchForm.addEventListener('submit', function() {
                if (autocompleteList) autocompleteList.remove();
            });
        });
    </script>

</body>
</html>