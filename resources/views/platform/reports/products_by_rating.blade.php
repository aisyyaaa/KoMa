@extends('layouts.platform')

@section('title', 'Laporan Daftar Produk')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- PAGE HEADER (SRS-MartPlace-11) --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold text-gray-800">Laporan Daftar Produk (SRS-11)</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Daftar produk dan ratingnya yang diurutkan berdasarkan rating secara menurun.</p>
        </div>
        
        {{-- Rute Export: Menggunakan 'products_by_rating' agar cocok dengan Controller --}}
        <a href="{{ route('platform.reports.export', 'products_by_rating') }}" target="_blank" rel="noopener noreferrer"
             class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export PDF
        </a>
    </div>

    {{-- REPORT TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toko</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provinsi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $index => $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $products->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        {{-- Memastikan Str diimpor jika ini digunakan di layout/controller --}}
                        <div class="text-xs text-gray-500">{{ Str::limit($product->description, 50) }}</div> 
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->seller->store_name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    {{-- REVISI KRITIS: Menggunakan $product->seller->province --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->seller->province ?? '-' }}</td> 
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            {{-- Gunakan reviews_avg_rating dan reviews_count --}}
                            <span class="ml-1 text-sm font-bold text-gray-900">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span>
                            <span class="ml-1 text-xs text-gray-500">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Tidak ada produk untuk ditampilkan.</h3>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($products->hasPages())
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection