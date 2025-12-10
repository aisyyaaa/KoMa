@extends('layouts.platform')

@section('title', 'Laporan Penjual Lokasi')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold text-gray-800">Laporan Penjual Lokasi</h1>
                <div class="relative">
                    <select onchange="window.location.href=this.value" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="{{ route('platform.reports.active_sellers') }}">Laporan Penjual Aktif</option>
                        <option value="{{ route('platform.reports.sellers_by_province') }}" selected>Laporan Penjual Lokasi</option>
                        <option value="{{ route('platform.reports.products_by_rating') }}">Laporan Daftar Produk</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-1">Daftar penjual (toko) untuk setiap lokasi provinsi.</p>
        </div>
          <a href="{{ route('platform.reports.export', 'seller-locations') }}" target="_blank" rel="noopener noreferrer"
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provinsi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Toko</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php $total = $byProvince->sum('total'); @endphp
                @forelse($byProvince->sortByDesc('total') as $index => $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">{{ $item->pic_province ?: 'Tidak Diketahui' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $item->total }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $total > 0 ? round(($item->total / $total) * 100, 1) : 0 }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900">Tidak ada data penjual untuk ditampilkan.</h3>
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
</div>
@endsection
