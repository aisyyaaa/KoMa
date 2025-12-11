<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KoMa Market - Landing Page</title>
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
        // Catatan: Variabel ini hanya bernilai true untuk request pertama setelah redirect
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
            
            {{-- LOGO SEBAGAI LINK KE LANDING PAGE --}}
            <a href="{{ route('landingpage.index') }}" class="text-2xl font-bold text-koma-primary hover:text-koma-danger transition duration-150">
                KoMa Market
            </a>
            
            <div class="flex-1 max-w-xl mx-8">
                <div class="relative">
                    <input type="text" placeholder="Cari produk atau toko..." class="w-full p-2 border-2 border-koma-primary rounded-lg focus:outline-none focus:border-koma-accent">
                    <button class="absolute right-0 top-0 h-full px-4 bg-koma-primary text-white rounded-r-lg hover:bg-koma-danger">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </div>

            <a href="{{ route('login') }}" class="text-white bg-koma-accent hover:bg-koma-hover-light hover:text-koma-text-dark font-medium border border-koma-accent rounded-md px-3 py-2 transition duration-150">
                Login
            </a>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="container mx-auto px-4 mt-6">
        {{-- ... (Semua konten utama tidak berubah) ... --}}
        
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
        
        {{-- 2. CATEGORY LIST (5 ITEM) --}}
        <div class="py-6"> 
            <h2 class="text-2xl font-bold border-b pb-2 mb-6 text-koma-text-dark">Jelajahi Kategori Pilihan</h2>
            
            <div class="flex space-x-4 overflow-x-auto pb-4 scrollbar-hide">
                
                @php
                    // Daftar Kategori Final dengan SVG INTERNAL
                    $categories = [
                        [
                            'name' => 'Alat Tulis & Kuliah', 
                            'color' => 'koma-accent', 
                            'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.205 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.795 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.795 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.205 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>' 
                        ], 
                        [
                            'name' => 'Kebutuhan Kos/Asrama', 
                            'color' => 'koma-primary', 
                            'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>' 
                        ], 
                        [
                            'name' => 'Buku, Modul, Skripsi', 
                            'color' => 'koma-accent', 
                            'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.205 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.795 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.795 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.205 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>'
                        ], 
                        [
                            'name' => 'Makanan & Minuman Instan', 
                            'color' => 'koma-primary', 
                            'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 00-7.072 0l-2.007 2.007c-.429.429-.429 1.125 0 1.554l7.072 7.072a5 5 0 007.072 0l2.007-2.007c.429-.429.429-1.125 0-1.554l-7.072-7.072zM5.536 18.464a5 5 0 007.072 0"></path></svg>'
                        ],
                        [
                            'name' => 'Aksesoris Gadget & Komputer', 
                            'color' => 'koma-accent', 
                            'icon_svg' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-75-.75M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>'
                        ]
                    ];
                @endphp

                @foreach ($categories as $index => $category)
                    <a href="#" class="flex-shrink-0 w-32 h-36 flex flex-col items-center justify-center p-3 rounded-xl border border-koma-bg-light shadow-md hover:shadow-xl hover:bg-koma-hover-light transition duration-150 group">
                        
                        {{-- SVG ICON INTERNAL (DIJAMIN MUNCUL) --}}
                        <div class="w-16 h-16 bg-koma-bg-light rounded-xl flex items-center justify-center mb-3 group-hover:bg-{{ $category['color'] }} transition duration-150 text-{{ $category['color'] }} group-hover:text-white">
                            {!! $category['icon_svg'] !!} {{-- Render SVG dari string --}}
                        </div>
                        
                        <span class="text-sm font-medium text-center text-koma-text-dark">
                            {{ $category['name'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 3. FEATURED PRODUCTS (Placeholder Gambar & Harga Merah) --}}
        <div class="py-6"> 
            <h2 class="text-2xl font-bold border-b pb-2 mb-6 text-koma-text-dark">Produk Pilihan KoMa</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @for ($i = 0; $i < 5; $i++)
                    <div class="bg-white border border-koma-bg-light rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                        <div class="h-40 overflow-hidden">
                            <img src="https://via.placeholder.com/300x200/dde0e7/5d5e62?text=Produk+{{ $i + 1 }}" alt="Gambar Produk" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-3">
                            <h3 class="text-md font-semibold text-koma-text-dark truncate">Nama Produk Keren dan Panjang</h3>
                            <p class="text-lg font-bold text-koma-primary mt-1">Rp 120.000</p>
                            <p class="text-xs text-gray-500 mt-1">KoMa Store</p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
        
    </main>
    
    {{-- FOOTER --}}
    <footer class="mt-10 py-6 bg-koma-accent text-white text-center text-sm">
        &copy; 2025 KoMa Market. Koperasi Mahasiswa. All Rights Reserved.
    </footer>

</body>
</html>

</body>
</html>
