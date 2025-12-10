@extends('layouts.platform')

@section('title', 'Tambah Produk (Platform)')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru (Platform)</h1>
        <p class="text-sm text-gray-500 mt-1">Tambahkan produk atas nama penjual atau administrasi.</p>
    </div>

    <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Informasi Dasar</h2>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                <input type="text" id="name" name="name" placeholder="Contoh: Buku Python" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
            </div>
        </div>
        <div class="flex items-center justify-end gap-4 pt-4 border-t">
            <a href="{{ route('platform.products.index') }}" class="px-6 py-2 bg-gray-100 text-gray-800 rounded-lg">Batal</a>
            <button type="submit" class="px-6 py-2 bg-koma-primary text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
