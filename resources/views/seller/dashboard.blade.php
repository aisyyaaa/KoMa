<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - KoMa Market</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        
        {{-- SIDEBAR --}}
        <div class="w-64 bg-white shadow-lg flex flex-col">
            
            {{-- APP LOGO/NAME --}}
            <div class="px-6 py-6 border-b border-gray-200">
                <h1 class="text-2xl font-extrabold text-koma-primary">KoMa</h1>
                <p class="text-xs text-gray-500 mt-1">Seller Panel</p>
            </div>

            {{-- SIDEBAR MENU --}}
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                
                {{-- Dashboard --}}
                <a href="{{ route('seller.dashboard') }}" 
                   class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium 
                          bg-koma-primary text-white hover:bg-koma-danger transition duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4"></path>
                    </svg>
                    Dashboard
                </a>

                {{-- Produk --}}
                <a href="{{ route('seller.products.index') }}" 
                   class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-koma-bg-light transition duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l5 2.5m5-2.5L12 17"></path>
                    </svg>
                    Produk
                </a>

                {{-- Pesanan --}}
                <a href="{{ route('seller.orders.index') }}" 
                   class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-koma-bg-light transition duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2z"></path>
                    </svg>
                    Pesanan
                </a>

                {{-- Ulasan --}}
                <a href="{{ route('seller.reviews.index') }}" 
                   class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-koma-bg-light transition duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Ulasan
                </a>

                {{-- Laporan --}}
                <a href="{{ route('seller.reports.index') }}" 
                   class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-koma-bg-light transition duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Laporan
                </a>

                     {{-- Profil --}}
                     <a href="{{ route('seller.profile.edit', auth()->check() ? auth()->user()->id : 1) }}" 
                   class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-gray-700
                          hover:bg-koma-bg-light transition duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Profil
                </a>

            </nav>

            {{-- LOGOUT BUTTON --}}
            <div class="px-4 py-4 border-t border-gray-200">
                @if(auth()->check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-red-600
                                   hover:bg-red-50 transition duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
                     @else
                     <a href="{{ route('login') }}" 
                   class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-koma-primary
                          hover:bg-koma-bg-light transition duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex items-center justify-end">
                    
                    {{-- PROFILE SECTION --}}
                    <div class="flex items-center space-x-4">
                        
                        {{-- SELLER NAME & STORE --}}
                        <div class="text-right">
                            @if(auth()->check())
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->seller->store_name ?? 'Toko' }}</p>
                            @else
                            <p class="text-sm font-semibold text-gray-900">Demo Seller</p>
                            <p class="text-xs text-gray-500">KoMa Store</p>
                            @endif
                        </div>

                        {{-- PROFILE AVATAR --}}
                        <div class="w-10 h-10 rounded-full bg-koma-primary text-white flex items-center justify-center font-semibold">
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

    @vite('resources/js/app.js')
</body>
</html>
