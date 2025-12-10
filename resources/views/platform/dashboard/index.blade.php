@extends('layouts.platform')

@section('title', 'Dashboard Platform')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Platform</h1>

        {{-- Stats Cards: Penjual Aktif/Tidak Aktif + Pengunjung yang Berkomentar --}}
        @include('platform.dashboard._stats-cards')

        {{-- Charts: Produk per Kategori & Toko per Provinsi --}}
        <div class="mt-6">
            @include('platform.dashboard._charts')
        </div>
    </div>
@endsection

