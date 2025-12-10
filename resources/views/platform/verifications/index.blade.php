@extends('layouts.platform')

@section('title', 'Verifikasi Penjual')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Verifikasi Penjual</h1>
            <p class="text-sm text-gray-500">Daftar calon penjual yang menunggu verifikasi.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Telp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pending as $i => $seller)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $i + 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $seller->store_name ?? $seller->pic_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $seller->pic_phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $seller->pic_email }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if(strtoupper($seller->status) === 'PENDING')
                            <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Menunggu</span>
                        @elseif(strtoupper($seller->status) === 'ACTIVE')
                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('platform.verifications.sellers.show', $seller) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded text-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-sm text-gray-500">Tidak ada calon penjual untuk diverifikasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
