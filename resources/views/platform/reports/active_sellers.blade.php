@extends('layouts.platform')

@section('title', 'Laporan Penjual Berdasarkan Status')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold text-gray-800">Laporan Penjual Berdasarkan Status (SRS-09)</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Daftar akun penjual aktif, tidak aktif, dan pending di platform KoMa.</p>
        </div>
          <a href="{{ route('platform.reports.export', 'seller-accounts') }}" target="_blank" rel="noopener noreferrer"
              class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export PDF
        </a>
    </div>

    {{-- STATS SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Penjual Aktif</p>
            <p class="text-2xl font-bold text-gray-900">{{ $active ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Non-Aktif/Pending</p>
            <p class="text-2xl font-bold text-gray-900">{{ $inactive ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-gray-500">
            <p class="text-sm text-gray-500">Total Akun Terdaftar</p>
            <p class="text-2xl font-bold text-gray-900">{{ $total ?? 0 }}</p>
        </div>
    </div>

    {{-- REPORT TABLE: Full seller list ordered by Status (ACTIVE first) --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Toko</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama PIC</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email PIC</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon PIC</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk Aktif</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Diperbarui</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sellers as $index => $seller)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $seller->store_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $seller->pic_name ?? '-' }}</td>
                    {{-- Menggunakan field pic_email dan pic_phone yang di-map di Controller --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $seller->pic_email ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $seller->pic_phone ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $status = strtoupper($seller->status);
                            $statusClass = [
                                'ACTIVE' => 'bg-green-100 text-green-800',
                                'PENDING' => 'bg-yellow-100 text-yellow-800',
                                'REJECTED' => 'bg-red-100 text-red-800',
                            ][$status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            {{ $seller->is_active_status }} {{-- Menggunakan status yang sudah di-map di Controller --}}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $seller->products_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ optional($seller->last_active)->format('Y-m-d H:i') ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12 text-sm text-gray-500">Belum ada data penjual untuk ditampilkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection