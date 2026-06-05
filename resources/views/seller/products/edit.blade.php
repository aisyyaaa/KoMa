@extends('layouts.seller')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-4xl mx-auto">
    
    {{-- PAGE HEADER --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Edit Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui informasi untuk produk: <span class="font-semibold">{{ $product->name }}</span></p>
    </div>

    {{-- MAIN FORM --}}
    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- BASIC INFORMATION --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Informasi Dasar</h2>

            {{-- NAMA PRODUK --}}
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" 
                       class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                       required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- DESKRIPSI --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk *</label>
                <textarea id="description" name="description" rows="5"
                          class="w-full px-4 py-2 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                          required>{{ old('description', $product->description) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Minimal 20 karakter untuk deskripsi yang informatif.</p>
                @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- KATEGORI & BRAND --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select id="category_id" name="category_id" class="w-full px-4 py-2 border @error('category_id') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition" required>
                        <option value="">-- Pilih Kategori --</option>
                        @forelse($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @empty
                        <option disabled>Tidak ada kategori</option>
                        @endforelse
                    </select>
                    @error('category_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('brand')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- SKU (Read Only) --}}
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU (Kode Unik Produk)</label>
                <input type="text" id="sku" value="{{ $product->sku }}" readonly
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                <p class="text-xs text-gray-500 mt-1">SKU tidak dapat diubah setelah produk dibuat.</p>
            </div>
        </div>

        {{-- PRICING & STOCK --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Harga & Stok</h2>

            {{-- HARGA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">Rp</span>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" 
                               class="w-full pl-10 pr-4 py-2 border @error('price') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                               required>
                    </div>
                    @error('price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Diskon (Opsional)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">Rp</span>
                        <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    </div>
                    @error('discount_price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- STOK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                           class="w-full px-4 py-2 border @error('stock') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                           required>
                    @error('stock')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum Peringatan</label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('min_stock')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- PRODUCT IMAGES --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Gambar Produk</h2>

            {{-- CURRENT PRIMARY IMAGE --}}
            @if($product->primary_image_url)
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Utama Saat Ini</label>
                <div class="relative w-40 h-40 rounded-lg overflow-hidden border">
                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            </div>
            @endif

            {{-- UPLOAD NEW IMAGE --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $product->primary_image_url ? 'Ganti Foto Utama' : 'Upload Foto Utama *' }}</label>
                <div id="primary-image-dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-koma-primary hover:bg-gray-50 transition duration-150">
                    <input type="file" name="primary_image" accept="image/*" class="hidden" id="primary-image-edit">
                    <label for="primary-image-edit" class="cursor-pointer block">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p id="primary-image-text" class="text-gray-600 font-medium">Klik atau drag gambar ke sini</p>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Maks 5MB)</p>
                    </label>
                </div>
                @error('primary_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- ADDITIONAL IMAGES --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Tambahan</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-koma-accent hover:bg-gray-50 transition duration-150">
                        <input type="file" name="additional_images[]" accept="image/*" class="hidden" id="additional-edit-{{ $i }}">
                        <label for="additional-edit-{{ $i }}" class="cursor-pointer block">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <p class="text-xs text-gray-500">Foto {{ $i }}</p>
                        </label>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- SHIPPING & ADDITIONAL INFO --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Pengiriman & Info Tambahan</h2>

            {{-- BERAT & DIMENSI --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Berat (gram)</label>
                    <input type="number" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('weight')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="length" class="block text-sm font-medium text-gray-700 mb-1">Panjang (cm)</label>
                    <input type="number" id="length" name="length" value="{{ old('length', $product->length) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('length')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="width" class="block text-sm font-medium text-gray-700 mb-1">Lebar (cm)</label>
                    <input type="number" id="width" name="width" value="{{ old('width', $product->width) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('width')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- KONDISI --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Produk *</label>
                <div class="flex space-x-6">
                    <label class="flex items-center">
                        <input type="radio" name="condition" value="new" class="w-4 h-4 text-koma-primary focus:ring-koma-primary" {{ old('condition', $product->condition) === 'new' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Baru</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="condition" value="used" class="w-4 h-4 text-koma-primary focus:ring-koma-primary" {{ old('condition', $product->condition) === 'used' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Bekas</span>
                    </label>
                </div>
                @error('condition')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- GARANSI --}}
            <div>
                <label for="warranty" class="block text-sm font-medium text-gray-700 mb-1">Garansi (bulan)</label>
                <input type="number" id="warranty" name="warranty" value="{{ old('warranty', $product->warranty) }}" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada garansi.</p>
                @error('warranty')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- FORM ACTIONS --}}
        <div class="flex items-center justify-end gap-4 pt-4 border-t">
            <a href="{{ route('seller.products.detail', ['id' => $product->id]) }}" class="px-6 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-koma-primary text-white rounded-lg hover:bg-koma-danger focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-primary transition duration-200 font-medium">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const primaryImageInput = document.getElementById('primary-image-edit');
        const primaryImageText = document.getElementById('primary-image-text');
        
        primaryImageInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                primaryImageText.textContent = '✓ ' + e.target.files[0].name;
                document.getElementById('primary-image-dropzone').classList.add('border-green-500');
            } else {
                primaryImageText.textContent = 'Klik atau drag gambar ke sini';
                document.getElementById('primary-image-dropzone').classList.remove('border-green-500');
            }
        });
    });
</script>
@endpush
