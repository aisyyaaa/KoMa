<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div x-data="{ open: false }" class="flex min-h-screen">
            <!-- Sidebar -->
            <aside :class="{'w-64': open, 'w-20': !open}" class="flex-shrink-0 bg-gray-800 text-white transition-all duration-300 ease-in-out overflow-y-auto">
                <div class="flex items-center justify-between p-4 h-16">
                    <span x-show="open" class="text-xl font-bold">KoMa Platform</span>
                    <button @click="open = !open" class="p-2 rounded-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <nav class="mt-4">
                    <a href="{{ route('platform.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('platform.dashboard') ? 'bg-gray-900' : '' }}">
                        <i class="fas fa-tachometer-alt w-6 text-center"></i>
                        <span x-show="open" class="ml-4">Dashboard</span>
                    </a>
                    <a href="{{ route('platform.verifications.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('platform.verifications.*') ? 'bg-gray-900' : '' }}">
                        <i class="fas fa-user-check w-6 text-center"></i>
                        <span x-show="open" class="ml-4">Verifikasi Penjual</span>
                    </a>
                    <a href="{{ route('platform.sellers.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('platform.sellers.*') ? 'bg-gray-900' : '' }}">
                        <i class="fas fa-store w-6 text-center"></i>
                        <span x-show="open" class="ml-4">Daftar Penjual</span>
                    </a>
                    <a href="{{ route('platform.categories.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-700 {{ request()->routeIs('platform.categories.*') ? 'bg-gray-900' : '' }}">
                        <i class="fas fa-tags w-6 text-center"></i>
                        <span x-show="open" class="ml-4">Kategori</span>
                    </a>
                    <!-- Reports Dropdown -->
                    <div x-data="{ reportOpen: {{ request()->routeIs('platform.reports.*') ? 'true' : 'false' }} }">
                        <button @click="reportOpen = !reportOpen" class="w-full flex justify-between items-center px-4 py-3 hover:bg-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt w-6 text-center"></i>
                                <span x-show="open" class="ml-4">Laporan</span>
                            </div>
                            <i x-show="open" class="fas" :class="{'fa-chevron-down': !reportOpen, 'fa-chevron-up': reportOpen}"></i>
                        </button>
                        <div x-show="open && reportOpen" class="bg-gray-700">
                            <a href="{{ route('platform.reports.seller-accounts') }}" class="flex items-center py-2 px-4 pl-12 hover:bg-gray-600 {{ request()->routeIs('platform.reports.seller-accounts') ? 'font-bold' : '' }}">Akun Penjual</a>
                            <a href="{{ route('platform.reports.seller-locations') }}" class="flex items-center py-2 px-4 pl-12 hover:bg-gray-600 {{ request()->routeIs('platform.reports.seller-locations') ? 'font-bold' : '' }}">Lokasi Penjual</a>
                            <a href="{{ route('platform.reports.product-ratings') }}" class="flex items-center py-2 px-4 pl-12 hover:bg-gray-600 {{ request()->routeIs('platform.reports.product-ratings') ? 'font-bold' : '' }}">Rating Produk</a>
                        </div>
                    </div>
                </nav>
            </aside>

            <!-- Main content -->
            <div class="flex-1 flex flex-col">
                <header class="bg-white shadow-md h-16 flex items-center justify-between px-6">
                    <div>
                        @if (isset($header))
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ $header }}
                            </h2>
                        @endif
                    </div>
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2">
                            <div>{{ Auth::user()->name ?? 'Admin' }}</div>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('platform.auth.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6">
                    <!-- Session Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>
</html>
