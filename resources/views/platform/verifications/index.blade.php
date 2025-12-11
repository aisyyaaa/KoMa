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

    @if (session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-2 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notifikasi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sellers as $i => $seller)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $i + 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $seller->store_name ?? $seller->pic_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $seller->pic_phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $seller->pic_email }}</td>
                    <td class="px-6 py-4 text-sm">
                        <form action="{{ route('platform.verifications.sellers.status', $seller) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="border rounded px-2 py-1 text-sm">
                                <option value="PENDING" {{ $seller->status === 'PENDING' ? 'selected' : '' }}>Menunggu</option>
                                <option value="ACTIVE" {{ $seller->status === 'ACTIVE' ? 'selected' : '' }}>Diterima</option>
                                <option value="REJECTED" {{ $seller->status === 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <button type="submit" class="px-2 py-1 bg-gray-100 rounded text-xs">Simpan</button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('platform.verifications.sellers.show', $seller) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded text-sm">Detail</a>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <form action="{{ route('platform.verifications.sellers.notify', $seller) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded text-sm {{ $seller->status === 'ACTIVE' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}" {{ $seller->status === 'ACTIVE' ? '' : 'disabled' }}>
                                Kirim via Email
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-sm text-gray-500">Tidak ada penjual.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
