@extends('layouts.seller')

@section('title', 'Edit Pesanan #' . $order->id)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Pesanan #{{ $order->id }}</h1>
        <a href="{{ route('seller.orders.show', $order) }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('seller.orders.update', $order) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        {{-- Data Pembeli --}}
        <div class="bg-white rounded-xl shadow p-6 space-y-4">
            <h2 class="font-semibold text-gray-700 text-lg border-b pb-2">Data Pembeli</h2>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Pembeli</label>
                <input type="text" name="name" value="{{ old('name', $order->name) }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-koma-primary" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nomor HP</label>
                <input type="text" name="phone" value="{{ old('phone', $order->phone) }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-koma-primary" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Pengiriman</label>
                <textarea name="address" rows="3"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-koma-primary" required>{{ old('address', $order->address) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Metode Pembayaran</label>
                <select name="payment_method"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-koma-primary">
                    <option value="COD" {{ old('payment_method', $order->payment_method) == 'COD' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                    <option value="Transfer" {{ old('payment_method', $order->payment_method) == 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>
        </div>

        {{-- Produk --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-semibold text-gray-700 text-lg border-b pb-2 mb-4">Produk Dipesan</h2>

            <div id="items-container" class="space-y-3">
                @foreach($order->items as $i => $item)
                <div class="item-row flex gap-3 items-center">
                    <select name="items[{{ $i }}][product_id]"
                        class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-koma-primary product-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} — Rp {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="items[{{ $i }}][qty]" value="{{ $item->qty }}" min="1"
                        class="w-24 border rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-koma-primary qty-input" required>
                    <button type="button" onclick="removeItem(this)"
                        class="text-red-400 hover:text-red-600 text-lg font-bold px-2">×</button>
                </div>
                @endforeach
            </div>

            <button type="button" onclick="addItem()"
                class="mt-4 text-sm text-koma-primary hover:underline">+ Tambah Produk</button>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-koma-primary text-white rounded-lg text-sm font-medium hover:opacity-90">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = {{ $order->items->count() }};
const productOptions = `{!! $products->map(fn($p) => '<option value="'.$p->id.'" data-price="'.$p->price.'">'.$p->name.' — Rp '.number_format($p->price,0,',','.').'</option>')->implode('') !!}`;

function addItem() {
    const container = document.getElementById('items-container');
    const row = document.createElement('div');
    row.className = 'item-row flex gap-3 items-center';
    row.innerHTML = `
        <select name="items[${itemIndex}][product_id]"
            class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-koma-primary product-select" required>
            <option value="">-- Pilih Produk --</option>
            ${productOptions}
        </select>
        <input type="number" name="items[${itemIndex}][qty]" value="1" min="1"
            class="w-24 border rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-koma-primary qty-input" required>
        <button type="button" onclick="removeItem(this)"
            class="text-red-400 hover:text-red-600 text-lg font-bold px-2">×</button>
    `;
    container.appendChild(row);
    itemIndex++;
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) btn.closest('.item-row').remove();
}
</script>
@endsection
