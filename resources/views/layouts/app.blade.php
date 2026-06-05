<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Katalog Produk Market Place</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- VITE (BAGIAN PALING PENTING) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">

        {{-- HEADER --}}
        <header class="bg-white border-b shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex justify-between items-center h-16">
                    
                    <div class="shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-extrabold text-indigo-700">
                            🛒 KoMa
                        </a>
                    </div>

                    <div class="flex-grow max-w-2xl mx-8">
                        <input type="text" placeholder="Cari produk, kategori, atau toko..." 
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5">
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/login') }}" class="text-gray-600 hover:text-indigo-700 font-medium">Login</a>
                        <a href="{{ route('seller.register') }}" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition duration-150">
                           Daftar Penjual
                        </a>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="py-12">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="bg-gray-900 text-white mt-auto py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                Marketplace 2025. Hak Cipta dan Kebijakan
            </div>
        </footer>

    </div>
</body>
</html>
