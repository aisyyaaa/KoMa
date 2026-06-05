@extends('layouts.platform')

@section('title', 'Laporan Penjual Lokasi')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold text-gray-800">Laporan Daftar Toko Berdasarkan Lokasi Provinsi (SRS-10)</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Daftar rinci penjual (toko) untuk setiap lokasi provinsi (termasuk Aktif dan Tidak Aktif).</p>
        </div>
          <a href="{{ route('platform.reports.export', 'seller-locations') }}" target="_blank" rel="noopener noreferrer"
              class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export PDF
        </a>
    </div>

    {{-- 1. RINGKASAN AGREGAT (Persentase Per Provinsi) --}}
    <h2 class="text-lg font-semibold text-gray-800 mb-3">Ringkasan Total Penjual per Provinsi</h2>
    <div class="bg-white rounded-lg shadow overflow-x-auto mb-8">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provinsi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Toko</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php $total = $byProvince->sum('total'); @endphp
                @forelse($byProvince->sortByDesc('total') as $index => $item)
                <tr>
                    {{-- FIX: Menggunakan (int) $index untuk memaksa tipe data integer (mengatasi TypeError) --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ (int) $index + 1 }}</td> 
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">{{ $item->province ?: 'Tidak Diketahui' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $item->total }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $total > 0 ? round(($item->total / $total) * 100, 1) : 0 }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Tidak ada data ringkasan penjual untuk ditampilkan.</h3>
                    </td>
                </tr>
                @endforelse
                @if($byProvince->count() > 0)
                <tr class="bg-gray-50">
                    <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $total }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">100%</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- 2. DAFTAR RINCI (SATU TABEL BESAR SESUAI SRS-10) --}}
    <h2 class="text-lg font-semibold text-gray-800 mb-3 mt-10">Daftar Rinci Toko (Diurutkan berdasarkan Provinsi)</h2>
    
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" style="width: 5%;" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" style="width: 30%;" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Toko</th>
                    <th scope="col" style="width: 30%;" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama PIC</th>
                    <th scope="col" style="width: 20%;" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provinsi</th>
                    <th scope="col" style="width: 15%;" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- REVISI KRITIS: Meratakan koleksi ganda ($groupedSellers) untuk iterasi satu tabel besar --}}
                @php $flatSellers = $groupedSellers->flatten()->sortBy('province'); @endphp 
                @forelse($flatSellers as $i => $seller) 
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 text-center">{{ $i + 1 }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $seller->store_name }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $seller->pic_name }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $seller->province }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm">
                        {{-- Menampilkan Status (Aktif, Pending, Rejected) --}}
                        <span class="font-semibold @if($seller->status === 'ACTIVE') text-green-600 @elseif($seller->status === 'PENDING') text-yellow-600 @else text-red-600 @endif">
                            {{ $seller->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-sm text-gray-500">Tidak ada penjual yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection