<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - KoMa Market</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">

    <div class="flex h-screen">
        
        {{-- SIDEBAR --}}
        <div class="w-56 bg-white border-r border-gray-200 flex flex-col shadow-md">
            
            {{-- APP LOGO/NAME --}}
            <div class="px-6 py-5 border-b border-gray-200">
                <h1 class="text-xl font-extrabold text-koma-primary leading-tight">KoMa</h1>
                <p class="text-xs text-gray-500 mt-1 font-medium">SELLER PANEL</p>
            </div>

            {{-- SIDEBAR MENU --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                
                {{-- Dashboard --}}
                <a href="{{ route('seller.dashboard') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium 
                          bg-koma-primary text-white hover:bg-koma-danger transition duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4"></path>
                    </svg>
                    Dashboard
                </a>

                {{-- Produk --}}
                <a href="{{ route('seller.products.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l5 2.5m5-2.5L12 17"></path>
                    </svg>
                    Produk
                </a>

                {{-- Pesanan --}}
                <a href="{{ route('seller.orders.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2z"></path>
                    </svg>
                    Pesanan
                </a>

                {{-- Ulasan --}}
                <a href="{{ route('seller.reviews.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Ulasan
                </a>

                {{-- Laporan --}}
                <a href="{{ route('seller.reports.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Laporan
                </a>

                {{-- Profil --}}
                <a href="{{ route('seller.profile.edit', auth()->check() ? auth()->user()->id : 1) }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Profil
                </a>

            </nav>

            {{-- LOGOUT BUTTON --}}
            <div class="px-3 py-3 border-t border-gray-200">
                @if(auth()->check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-red-600
                                   hover:bg-red-50 transition duration-150">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
                @else
                <a href="{{ route('seller.auth.login') }}" 
                   class="w-full flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-koma-primary
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1"></path>
                    </svg>
                    Login
                </a>
                @endif
            </div>

        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col">
            
            {{-- NAVBAR --}}
            <nav class="bg-white shadow-sm border-b border-gray-200 h-14 flex items-center px-5">
                <div class="flex items-center justify-between w-full">
                    
                    {{-- SEARCH BAR --}}
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" placeholder="Cari..." 
                                   class="w-full px-3 py-2 rounded-lg bg-gray-100 border-0 focus:ring-2 focus:ring-koma-primary focus:bg-white text-sm">
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- PROFILE SECTION --}}
                    <div class="flex items-center space-x-2.5 ml-6">
                        
                        {{-- SELLER NAME & STORE --}}
                        <div class="text-right">
                            @if(auth()->check())
                            <p class="text-xs font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->seller->store_name ?? 'Toko' }}</p>
                            @else
                            <p class="text-xs font-semibold text-gray-900">Demo Seller</p>
                            <p class="text-xs text-gray-500">KoMa Store</p>
                            @endif
                        </div>

                        {{-- PROFILE AVATAR --}}
                        <div class="w-7 h-7 rounded-full bg-koma-primary text-white flex items-center justify-center text-xs font-semibold">
                            @if(auth()->check())
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @else
                            D
                            @endif
                        </div>

                    </div>

                </div>
            </nav>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>
                    
                    {{-- STATS CARDS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        {{-- Card 1: Total Sales --}}
                        <div class="bg-white p-5 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-koma-primary bg-opacity-10 text-koma-primary">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01V5M12 20v-1m0 1v.01M12 18v-1m0-1v-1m-4-5H7m14 0h-1M7 12H6m2-5h1M6 7h1m11 5h-1m-1 5h1m-1-5h-1m-1 5h1m-1-5h-1"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                                    <p class="text-xl font-bold text-gray-800">Rp 12.580.000</p>
                                </div>
                            </div>
                        </div>
                        {{-- Card 2: Orders --}}
                        <div class="bg-white p-5 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10 text-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Pesanan Baru</p>
                                    <p class="text-xl font-bold text-gray-800">34</p>
                                </div>
                            </div>
                        </div>
                        {{-- Card 3: Products --}}
                        <div class="bg-white p-5 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-500 bg-opacity-10 text-green-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Total Produk</p>
                                    <p class="text-xl font-bold text-gray-800">125</p>
                                </div>
                            </div>
                        </div>
                        {{-- Card 4: Reviews --}}
                        <div class="bg-white p-5 rounded-lg shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Ulasan Baru</p>
                                    <p class="text-xl font-bold text-gray-800">12</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TABS --}}
                    <div x-data="{ activeTab: 'ringkasan' }" class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                                <button @click="activeTab = 'ringkasan'" 
                                        :class="activeTab === 'ringkasan' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Ringkasan
                                </button>
                                <button @click="activeTab = 'produk'"
                                        :class="activeTab === 'produk' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Analisis Produk
                                </button>
                                <button @click="activeTab = 'pelanggan'"
                                        :class="activeTab === 'pelanggan' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Analisis Pelanggan
                                </button>
                            </nav>
                        </div>

                        {{-- TAB PANELS --}}
                        <div class="py-6">
                            {{-- Ringkasan Panel --}}
                            <div x-show="activeTab === 'ringkasan'" x-transition>
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Penjualan (7 Hari Terakhir)</h3>
                                        <div class="relative h-80"><canvas id="salesTrendChart"></canvas></div>
                                    </div>
                                    <div class="bg-white p-6 rounded-lg shadow">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pesanan</h3>
                                        <div class="relative h-80"><canvas id="orderStatusChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                            {{-- Analisis Produk Panel --}}
                            <div x-show="activeTab === 'produk'" x-transition style="display: none;">
                                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
                                    <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terlaris</h3>
                                        <div class="relative h-96"><canvas id="topProductsChart"></canvas></div>
                                    </div>
                                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Rating per Produk</h3>
                                        <div class="relative h-96"><canvas id="productRatingsChart"></canvas></div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-lg shadow">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sebaran Stok Produk</h3>
                                    <div class="relative h-96"><canvas id="stockDistributionChart"></canvas></div>
                                </div>
                            </div>
                            {{-- Analisis Pelanggan Panel --}}
                            <div x-show="activeTab === 'pelanggan'" x-transition style="display: none;">
                                <div class="grid grid-cols-1">
                                    <div class="bg-white p-6 rounded-lg shadow">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sebaran Lokasi Rating</h3>
                                        <div class="relative h-96"><canvas id="raterLocationChart"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
