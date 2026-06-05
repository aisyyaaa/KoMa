@extends('layouts.seller')

@section('title', 'Produk')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ deleteModal: false, deleteFormUrl: '' }">
    
    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Produk Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua produk toko Anda di sini. (SRS-MartPlace-03)</p>
        </div>
        <a href="{{ route('seller.products.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-koma-primary text-white rounded-lg shadow-sm hover:bg-koma-danger focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-primary transition duration-150">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Produk
        </a>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="bg-white rounded-lg shadow p-5 mb-6">
        <form method="GET" action="{{ route('seller.products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" id="search" placeholder="Cari nama produk atau SKU..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                </div>
            </div>
            <div>
                <label for="category" class="sr-only">Kategori</label>
                <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    <option value="">Semua Kategori</option>
                    @forelse($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @empty
                    <option disabled>Tidak ada kategori</option>
                    @endforelse
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-koma-accent hover:bg-koma-nav focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-accent transition">
                    Filter
                </button>
                @if(request('search') || request('category'))
                <a href="{{ route('seller.products.index') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-primary transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- PRODUCTS TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                {{-- FIX KRITIS: Menggunakan accessor primary_image_url --}}
                                <img class="h-10 w-10 rounded-md object-cover" 
                                    src="{{ $product->primary_image_url }}" 
                                    alt="{{ $product->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->stock > $product->min_stock)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $product->stock }} Tersedia
                            </span>
                        @elseif($product->stock > 0)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $product->stock }} Sisa sedikit
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Habis
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            {{-- Menggunakan rating_average dari Model/DB --}}
                            <span class="ml-1 text-sm font-medium text-gray-700">
                                {{ number_format($product->rating_average, 1) }}
                            </span>
                            {{-- Menggunakan reviews_count (asumsi Controller menggunakan withCount) --}}
                            <span class="text-sm text-gray-500 ml-1">({{ $product->reviews_count ?? 0 }} ulasan)</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('seller.products.detail', ['id' => $product->id]) }}" class="text-gray-500 hover:text-koma-primary p-1 rounded-full transition duration-150">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('seller.products.edit', $product->id) }}" class="text-gray-500 hover:text-koma-accent p-1 rounded-full transition duration-150">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path></svg>
                            </a>
                            {{-- Tombol Hapus: Membuka modal konfirmasi --}}
                            <button 
                                @click.prevent="deleteModal = true; deleteFormUrl = '{{ route('seller.products.destroy', $product->id) }}'"
                                type="button" class="text-gray-500 hover:text-red-500 p-1 rounded-full transition duration-150">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12l5 2.5m5-2.5L12 17"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada produk ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian Anda atau tambahkan produk baru.</p>
                            <div class="mt-6">
                                <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-koma-primary hover:bg-koma-danger focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-primary">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Produk Baru
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if (isset($products) && $products->hasPages())
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif

    {{-- DELETE CONFIRMATION MODAL --}}
    <div x-show="deleteModal" x-cloak style="display: none;"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.away="deleteModal = false"
             class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-auto"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
            <div class="flex items-start">
                <div class="flex-shrink-0 mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4 text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Hapus Produk
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan. Semua data produk akan dihapus secara permanen.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form :action="deleteFormUrl" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                        Hapus
                    </button>
                </form>
                <button @click="deleteModal = false" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-primary sm:mt-0 sm:w-auto sm:text-sm transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection