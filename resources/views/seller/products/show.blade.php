@extends('layouts.seller')

@section('title', 'Detail Produk')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- PAGE HEADER --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Detail lengkap untuk produk Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('seller.products.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-primary transition duration-150 text-sm font-medium">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <a href="{{ route('seller.products.edit', $product->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-koma-accent text-white rounded-lg shadow-sm hover:bg-koma-nav focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-accent transition duration-150 text-sm font-medium">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path></svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- PRODUCT IMAGES & DETAILS (LEFT & MIDDLE) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- MAIN IMAGE & THUMBNAILS --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="bg-gray-100 rounded-lg flex items-center justify-center h-96 mb-4 overflow-hidden">
                    @if($product->primary_image_url)
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain">
                    @else
                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                </div>
                <div class="grid grid-cols-5 gap-3">
                    @php
                        $additional = $product->additional_image_urls ?? [];
                        $images = [];
                        if ($product->primary_image_url) {
                            $images[] = $product->primary_image_url;
                        }
                        $images = array_merge($images, $additional);
                        // ensure 5 slots
                        $images = array_pad($images, 5, null);
                    @endphp

                    @foreach($images as $i => $img)
                    <div class="bg-gray-200 rounded-lg cursor-pointer border-2 {{ $i == 0 ? 'border-koma-primary' : 'border-transparent' }} hover:border-koma-primary transition duration-150">
                        @if($img)
                        <div class="aspect-square rounded bg-cover bg-center" style="background-image: url('{{ $img }}')"></div>
                        @else
                        <div class="aspect-square rounded bg-center flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- PRODUCT DETAILS TABS --}}
            <div class="bg-white rounded-lg shadow" x-data="{ tab: 'info' }">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-6 px-6" aria-label="Tabs">
                        <button @click="tab = 'info'" 
                                :class="tab === 'info' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                            Informasi Produk
                        </button>
                        <button @click="tab = 'reviews'"
                                :class="tab === 'reviews' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                            Ulasan Pembeli ({{ $product->reviews_count ?? 0 }})
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    {{-- INFO TAB --}}
                    <div x-show="tab === 'info'" x-transition>
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi</h3>
                                <p class="text-gray-600 leading-relaxed whitespace-pre-wrap">
                                    {{ $product->description }}
                                </p>
                            </div>
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Detail</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    <div class="flex flex-col"><dt class="text-sm font-medium text-gray-500">SKU</dt><dd class="mt-1 text-sm text-gray-900">{{ $product->sku }}</dd></div>
                                    <div class="flex flex-col"><dt class="text-sm font-medium text-gray-500">Brand</dt><dd class="mt-1 text-sm text-gray-900">{{ $product->brand ?? '-' }}</dd></div>
                                    <div class="flex flex-col"><dt class="text-sm font-medium text-gray-500">Kategori</dt><dd class="mt-1 text-sm text-gray-900">{{ $product->category->name ?? '-' }}</dd></div>
                                    <div class="flex flex-col"><dt class="text-sm font-medium text-gray-500">Kondisi</dt><dd class="mt-1 text-sm text-gray-900">{{ $product->condition_label }}</dd></div>
                                    <div class="flex flex-col"><dt class="text-sm font-medium text-gray-500">Berat</dt><dd class="mt-1 text-sm text-gray-900">{{ $product->weight ? $product->weight.' gram' : '-' }}</dd></div>
                                    <div class="flex flex-col"><dt class="text-sm font-medium text-gray-500">Garansi</dt><dd class="mt-1 text-sm text-gray-900">{{ $product->warranty ? $product->warranty.' bulan' : 'Tidak ada' }}</dd></div>
                                </dl>
                            </div>
                        </div>
                    </div>
                    {{-- REVIEWS TAB --}}
                    <div x-show="tab === 'reviews'" x-transition style="display: none;">
                        <p class="text-gray-600">Fitur ulasan akan ditampilkan di sini.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- PRICING, STOCK, RATING (RIGHT) --}}
        <div class="space-y-6">
            
            {{-- PRICING CARD --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-3">Harga</h3>
                
                @if($product->discount_price && $product->discount_price < $product->price)
                    <p class="text-xl font-bold text-koma-danger">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                    <div class="flex items-center space-x-2">
                        <p class="text-sm text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @php $percent = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
                        <span class="text-xs font-semibold bg-red-100 text-red-700 px-2 py-0.5 rounded-md">{{ $percent }}% OFF</span>
                    </div>
                @else
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                @endif
            </div>

            {{-- STOCK CARD --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-3">Stok & Penjualan</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Stok Tersedia</p>
                        <p class="text-sm font-bold text-gray-800">{{ $product->stock }} unit</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Terjual</p>
                        <p class="text-sm font-bold text-gray-800">{{ $product->reviews_count ?? 0 }} unit</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Minimum Stok</p>
                        <p class="text-sm font-bold text-gray-800">{{ $product->min_stock ?? 0 }} unit</p>
                    </div>
                </div>
            </div>

            {{-- RATING CARD --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-3">Rating Produk</h3>
                <div class="flex items-center justify-center space-x-2 mb-2">
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($product->averageRating(), 1) }}</p>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= floor($product->averageRating()) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                </div>
                <p class="text-center text-sm text-gray-500">Berdasarkan {{ $product->reviews_count ?? 0 }} ulasan</p>
            </div>

            {{-- STATUS CARD --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-3">Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Publikasi</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Terakhir Diperbarui</span>
                        <span class="text-sm text-gray-600">{{ $product->updated_at->diffForHumans() }}</span>
                    </div>
                    <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini secara permanen? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full mt-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                            Hapus Produk
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
