@extends('layouts.seller')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pesanan Masuk</h1>
        <a href="{{ route('seller.orders.create') }}"
           class="px-4 py-2 bg-koma-primary text-white text-sm rounded-lg hover:opacity-90">
            + Buat Pesanan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <p class="text-lg">Belum ada pesanan masuk.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-600 font-semibold">#</th>
                        <th class="px-4 py-3 text-left text-gray-600 font-semibold">Pembeli</th>
                        <th class="px-4 py-3 text-left text-gray-600 font-semibold">Total</th>
                        <th class="px-4 py-3 text-left text-gray-600 font-semibold">Status</th>
                        <th class="px-4 py-3 text-left text-gray-600 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-left text-gray-600 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-700">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $order->name }}<br>
                            <span class="text-xs text-gray-400">{{ $order->phone }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $colors = [
                                    'Menunggu'  => 'bg-yellow-100 text-yellow-800',
                                    'Diproses'  => 'bg-blue-100 text-blue-800',
                                    'Dikirim'   => 'bg-indigo-100 text-indigo-800',
                                    'Selesai'   => 'bg-green-100 text-green-800',
                                ];
                                $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('seller.orders.show', $order) }}"
                               class="px-3 py-1.5 rounded-lg bg-koma-primary text-white text-xs hover:opacity-90">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
