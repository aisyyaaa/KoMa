@extends('layouts.platform')

@section('title', 'Dashboard Platform')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Platform (SRS-MartPlace-07)</h1>
    <p class="text-sm text-gray-500 mb-6">Pusat kontrol dan pemantauan aktivitas Marketplace.</p>

    {{-- Stats Cards (HANYA MUNCUL 4 METRIK SESUAI SRS-07) --}}
    {{-- Memastikan partial _stats-cards sudah direvisi untuk 4 kartu: Total, Aktif, Tidak Aktif, Komentator --}}
    @include('platform.dashboard._stats-cards')
    
    {{-- Charts: Produk per Kategori & Toko per Provinsi --}}
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Analitik Kinerja Platform</h2>
        {{-- Memastikan partial _charts sudah direvisi untuk grafik sebaran produk/kategori & toko/lokasi --}}
        @include('platform.dashboard._charts')
    </div>
    
</div>
@endsection