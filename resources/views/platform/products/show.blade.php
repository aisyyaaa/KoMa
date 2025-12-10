@extends('layouts.platform')

@section('title', 'Detail Produk (Platform)')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Detail produk di platform.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('platform.products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="bg-gray-100 rounded-lg flex items-center justify-center h-96 mb-4 overflow-hidden">
                    @if($product->primary_image_url)
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain">
                    @else
                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $product->description }}</p>
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-3">Harga</h3>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-3">Stok</h3>
                <p class="text-sm font-bold text-gray-800">{{ $product->stock }} unit</p>
            </div>
        </div>
    </div>
</div>
@endsection
