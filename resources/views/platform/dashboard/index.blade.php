@extends('layouts.platform')

@section('title', 'Dashboard Platform')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Platform (SRS-MartPlace-07)</h1>
    <p class="text-sm text-gray-500 mb-6">Pusat kontrol dan pemantauan aktivitas Marketplace.</p>

    {{-- Stats Cards (DIASUMSIKAN _stats-cards.blade.php SUDAH MEMUAT DATA) --}}
    @include('platform.dashboard._stats-cards')
    
    {{-- Aksi Kritis: Verifikasi Penjual (SRS-MartPlace-02) - DIHILANGKAN SESUAI PERMINTAAN --}}
    {{-- Button dan Status Verifikasi tidak ditampilkan di sini --}}


    {{-- Charts: Produk per Kategori & Toko per Provinsi --}}
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Analitik Kinerja Platform</h2>
        @include('platform.dashboard._charts')
    </div>
    
    {{-- Navigasi Laporan Tambahan (SRS-09, 10, 11) - DIHILANGKAN SESUAI PERMINTAAN --}}
    {{-- Bagian Akses Laporan Data tidak ditampilkan --}}

</div>
@endsection