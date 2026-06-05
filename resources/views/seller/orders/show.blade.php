@extends('layouts.seller')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">

    <div class="mb-4">
        <a href="{{ route('seller.orders.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali ke Daftar Pesanan</a>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
        <a href="{{ route('seller.orders.edit', $order) }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Edit Pesanan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Data Pembeli --}}
    <div class="bg-white rounded-xl shadow p-5 mb-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Data Pembeli</h2>
        <div class="grid grid-cols-2 gap-3 text-sm text-gray-600">
            <div><span class="font-medium">Nama:</span> {{ $order->name }}</div>
            <div><span class="font-medium">No HP:</span> {{ $order->phone }}</div>
            <div class="col-span-2"><span class="font-medium">Alamat:</span> {{ $order->address }}</div>
            <div><span class="font-medium">Metode Bayar:</span> {{ $order->payment_method }}</div>
            <div><span class="font-medium">Tanggal Order:</span> {{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
    </div>

    {{-- Produk Dipesan --}}
    <div class="bg-white rounded-xl shadow p-5 mb-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Produk Dipesan</h2>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="py-2 px-3 text-left text-gray-600">Produk</th>
                    <th class="py-2 px-3 text-center text-gray-600">Qty</th>
                    <th class="py-2 px-3 text-right text-gray-600">Harga Satuan</th>
                    <th class="py-2 px-3 text-right text-gray-600">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <tr>
                    <td class="py-2 px-3 text-gray-700">{{ $item->product->name ?? '-' }}</td>
                    <td class="py-2 px-3 text-center text-gray-700">{{ $item->qty }}</td>
                    <td class="py-2 px-3 text-right text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="py-2 px-3 text-right text-gray-700 font-medium">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t">
                <tr>
                    <td colspan="3" class="py-2 px-3 text-right font-bold text-gray-800">Total</td>
                    <td class="py-2 px-3 text-right font-bold text-gray-800">
                        Rp {{ number_format($order->total ?: $order->items->sum(fn($i) => $i->price * $i->qty), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Update Status --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Status Pesanan</h2>
        @php
            $allStatuses = ['Menunggu', 'Diproses', 'Dikirim', 'Selesai'];
            $currentIndex = array_search($order->status, $allStatuses);
            $nextStatuses = array_slice($allStatuses, $currentIndex + 1);
            $colors = [
                'Menunggu' => 'bg-yellow-100 text-yellow-800',
                'Diproses' => 'bg-blue-100 text-blue-800',
                'Dikirim'  => 'bg-indigo-100 text-indigo-800',
                'Selesai'  => 'bg-green-100 text-green-800',
            ];
        @endphp

        <div class="flex flex-col gap-3">
            <div>
                <p class="text-xs text-gray-400 mb-1">Status saat ini</p>
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $colors[$order->status] }}">
                    {{ $order->status }}
                </span>
            </div>

            @if($order->status !== 'Selesai')
            <div>
                <p class="text-xs text-gray-400 mb-1">Ubah status</p>
                <form method="POST" action="{{ route('seller.orders.update_status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()"
                            class="w-56 border border-gray-300 rounded-lg px-4 py-2.5 text-sm bg-white cursor-pointer hover:border-koma-primary focus:ring-2 focus:ring-koma-primary focus:outline-none">
                        <option value="" disabled selected>Pilih status baru...</option>
                        @foreach($nextStatuses as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
