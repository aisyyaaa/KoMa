<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Panel') - KoMa Market</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

    <x-notification />

    <div class="flex h-screen">
        
        {{-- SIDEBAR --}}
        <div class="w-56 bg-white border-r border-gray-200 flex flex-col shadow-md">
            
            {{-- APP LOGO/NAME --}}
            <div class="px-6 py-5 border-b border-gray-200">
                <a href="{{ route('katalog.index')}}">
                    <h1 class="text-xl font-extrabold text-koma-primary leading-tight">KoMa</h1>
                </a>
                <p class="text-xs text-gray-500 mt-1 font-medium">SELLER PANEL</p>
            </div>

            {{-- SIDEBAR MENU --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                
                {{-- Dashboard --}}
                <a href="{{ route('seller.dashboard') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium 
                          {{ request()->routeIs('seller.dashboard') ? 'bg-koma-primary text-white' : 'text-gray-700 hover:bg-gray-100' }} 
                          transition duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4"></path>
                    </svg>
                    Dashboard
                </a>

                {{-- Produk --}}
                <a href="{{ route('seller.products.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium 
                          {{ request()->routeIs('seller.products.*') ? 'bg-koma-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}
                          transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l5 2.5m5-2.5L12 17"></path>
                    </svg>
                    Produk
                </a>

                {{-- Pesanan --}}
                <a href="#" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2z"></path>
                    </svg>
                    Pesanan
                </a>

                {{-- Ulasan --}}
                <a href="#" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition duration-150">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Ulasan
                </a>

                {{-- Laporan --}}
                <div x-data="{ open: {{ request()->routeIs('seller.reports.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700
                                   hover:bg-gray-100 transition duration-150">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Laporan
                        </span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="mt-1 space-y-1 pl-8">
                        <a href="{{ route('seller.reports.stock_by_quantity') }}"
                           class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('seller.reports.stock_by_quantity') ? 'bg-gray-100 text-koma-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                            Stok per Kuantitas
                        </a>
                        <a href="{{ route('seller.reports.stock_by_rating') }}"
                           class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('seller.reports.stock_by_rating') ? 'bg-gray-100 text-koma-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                            Stok per Rating
                        </a>
                        <a href="{{ route('seller.reports.low_stock') }}"
                           class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('seller.reports.low_stock') ? 'bg-gray-100 text-koma-primary' : 'text-gray-600 hover:bg-gray-100' }}">
                            Stok Rendah
                        </a>
                    </div>
                </div>

                {{-- Profil --}}
                <a href="#" 
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
                     <a href="{{ route('login') }}" 
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
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>

        </div>
    </div>
    
    @stack('scripts')
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('show-notification', { detail: { type: 'success', message: {!! json_encode(session('success')) !!} } }));
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('show-notification', { detail: { type: 'error', message: {!! json_encode(session('error')) !!} } }));
        });
    </script>
    @endif
</body>
</html>
