@extends('layouts.seller')

@section('title', 'Pesanan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pesanan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pesanan pelanggan yang masuk.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @php
            $statusStyles = [
                'pending' => ['title' => 'Pending', 'color' => 'bg-yellow-100 text-yellow-800'],
                'processed' => ['title' => 'Diproses', 'color' => 'bg-blue-100 text-blue-800'],
                'shipped' => ['title' => 'Dikirim', 'color' => 'bg-indigo-100 text-indigo-800'],
                'completed' => ['title' => 'Selesai', 'color' => 'bg-green-100 text-green-800'],
            ];
        @endphp

        @foreach ($statusStyles as $status => $meta)
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-500">{{ $meta['title'] }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $orderStats[$status] ?? 0 }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">tanggal pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Edit status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($orders as $order)
                <tr>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-900">{{ $order['order_number'] }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">{{ $order['customer_name'] }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $order['product'] }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($order['total'], 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusStyles[$order['status']]['color'] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusStyles[$order['status']]['title'] ?? ucfirst($order['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ optional($order['created_at'])->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <form action="{{ route('seller.orders.update', $order['order_number']) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <select name="status" class="border border-gray-300 rounded-md text-sm focus:ring-koma-primary focus:border-koma-primary">
                                @foreach ($statusStyles as $value => $meta)
                                <option value="{{ $value }}" {{ $order['status'] === $value ? 'selected' : '' }}>
                                    {{ $meta['title'] }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3 py-1 text-xs font-semibold text-white bg-koma-primary rounded-md hover:bg-koma-danger transition">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        Belum ada pesanan yang masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
