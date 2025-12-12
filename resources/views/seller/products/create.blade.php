@extends('layouts.seller')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-4xl mx-auto">
    
    {{-- PAGE HEADER --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Isi informasi produk dengan lengkap untuk hasil yang maksimal.</p>
    </div>

    {{-- MAIN FORM --}}
    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- BASIC INFORMATION --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Informasi Dasar</h2>

            {{-- NAMA PRODUK --}}
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                <input type="text" id="name" name="name" placeholder="Contoh: Buku Python Fundamentals" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                        required>
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- DESKRIPSI --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk *</label>
                <textarea id="description" name="description" rows="5" placeholder="Jelaskan fitur, kondisi, dan detail produk Anda..." 
                              class="w-full px-4 py-2 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                              required>{{ old('description') }}</textarea>
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    <input type="text" id="brand" name="brand" placeholder="Nama brand produk" 
                            value="{{ old('brand') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('brand')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- SKU --}}
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU (Kode Unik Produk) *</label>
                <input type="text" id="sku" name="sku" placeholder="Contoh: BPF-001" 
                        value="{{ old('sku') }}"
                        class="w-full px-4 py-2 border @error('sku') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                        required>
                <p class="text-xs text-gray-500 mt-1">Gunakan kode yang mudah diingat dan unik.</p>
                @error('sku')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
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
                        <input type="number" id="price" name="price" placeholder="0" 
                                value="{{ old('price') }}"
                                class="w-full pl-10 pr-4 py-2 border @error('price') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                                required>
                    </div>
                    @error('price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Diskon (Opsional)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">Rp</span>
                        <input type="number" id="discount_price" name="discount_price" placeholder="0" 
                                value="{{ old('discount_price') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    </div>
                    @error('discount_price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- STOK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok *</label>
                    <input type="number" id="stock" name="stock" placeholder="0" min="0"
                            value="{{ old('stock') }}"
                            class="w-full px-4 py-2 border @error('stock') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                            required>
                    @error('stock')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum Peringatan</label>
                    <input type="number" id="min_stock" name="min_stock" placeholder="5" min="0"
                            value="{{ old('min_stock') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('min_stock')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- PRODUCT IMAGES --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Gambar Produk</h2>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Utama *</label>
                <div id="primary-image-dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-koma-primary hover:bg-gray-50 transition duration-150">
                    <input type="file" name="primary_images" accept="image/*" class="hidden" id="primary-image">
                    <label for="primary-image" class="cursor-pointer block">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p id="primary-image-text" class="text-gray-600 font-medium">Klik gambar ke sini</p>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Maks 5MB)</p>
                    </label>
                </div>
                @error('primary_images')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Tambahan (Opsional)</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                    <div id="additional-dropzone-{{ $i }}" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-koma-accent hover:bg-gray-50 transition duration-150">
                        <input type="file" name="additional_images[]" accept="image/*" class="hidden" id="additional-image-{{ $i }}">
                        <label for="additional-image-{{ $i }}" class="cursor-pointer block">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <p id="additional-text-{{ $i }}" class="text-xs text-gray-500">Foto {{ $i }}</p>
                        </label>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- SHIPPING & ADDITIONAL INFO --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-4">Pengiriman & Info Tambahan</h2>

            {{-- REVISI KRITIS: Kota Asal Pengiriman & Biaya Dasar --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="shipment_origin_city" class="block text-sm font-medium text-gray-700 mb-1">Dikirim Dari Kota *</label>
                    <input type="text" id="shipment_origin_city" name="shipment_origin_city" placeholder="Contoh: Tangerang Selatan" 
                            value="{{ old('shipment_origin_city') }}"
                            class="w-full px-4 py-2 border @error('shipment_origin_city') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition"
                            required>
                    @error('shipment_origin_city')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="base_shipping_cost" class="block text-sm font-medium text-gray-700 mb-1">Biaya Kirim Dasar (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">Rp</span>
                        <input type="number" id="base_shipping_cost" name="base_shipping_cost" placeholder="7500" 
                                value="{{ old('base_shipping_cost') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    </div>
                    @error('base_shipping_cost')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- BERAT & DIMENSI --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Berat (gram)</label>
                    <input type="number" id="weight" name="weight" placeholder="500" 
                            value="{{ old('weight') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('weight')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="length" class="block text-sm font-medium text-gray-700 mb-1">Panjang (cm)</label>
                    <input type="number" id="length" name="length" placeholder="10" 
                            value="{{ old('length') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('length')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="width" class="block text-sm font-medium text-gray-700 mb-1">Lebar (cm)</label>
                    <input type="number" id="width" name="width" placeholder="8" 
                            value="{{ old('width') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                    @error('width')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- KONDISI --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Produk *</label>
                <div class="flex space-x-6">
                    <label class="flex items-center">
                        <input type="radio" name="condition" value="new" class="w-4 h-4 text-koma-primary focus:ring-koma-primary" {{ old('condition', 'new') === 'new' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Baru</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="condition" value="used" class="w-4 h-4 text-koma-primary focus:ring-koma-primary" {{ old('condition') === 'used' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Bekas</span>
                    </label>
                </div>
                @error('condition')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- GARANSI --}}
            <div>
                <label for="warranty" class="block text-sm font-medium text-gray-700 mb-1">Garansi (bulan)</label>
                <input type="number" id="warranty" name="warranty" placeholder="0" min="0"
                        value="{{ old('warranty') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary transition">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada garansi.</p>
                @error('warranty')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- FORM ACTIONS --}}
        <div class="flex items-center justify-end gap-4 pt-4 border-t">
            <a href="{{ route('seller.products.index') }}" class="px-6 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-koma-primary text-white rounded-lg hover:bg-koma-danger focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koma-primary transition duration-200 font-medium">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Publikasikan Produk
            </button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- LOGIC FOR PRIMARY IMAGE ---
        const primaryImageInput = document.getElementById('primary-image');
        const primaryImageText = document.getElementById('primary-image-text');
        const primaryImageDropzone = document.getElementById('primary-image-dropzone');
        
        primaryImageInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                primaryImageText.textContent = '✓ ' + e.target.files[0].name;
                primaryImageDropzone.classList.add('border-green-500');
            } else {
                primaryImageText.textContent = 'Klik gambar ke sini';
                primaryImageDropzone.classList.remove('border-green-500');
            }
        });
        
        // --- LOGIC FOR ADDITIONAL IMAGES (THE FIX) ---
        const additionalInputs = document.querySelectorAll('input[name="additional_images[]"]');
        
        additionalInputs.forEach((input, index) => {
            const dropzone = input.closest('div');
            const textElement = document.getElementById(`additional-text-${index + 1}`);
            
            input.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    // Update text, add checkmark, and change border color
                    textElement.textContent = '✓ ' + e.target.files[0].name;
                    dropzone.classList.add('border-green-500');
                    dropzone.classList.remove('border-gray-300'); // Optional: cleanup original border
                } else {
                    // Reset if file is cancelled/removed
                    textElement.textContent = `Foto ${index + 1}`;
                    dropzone.classList.remove('border-green-500');
                    dropzone.classList.add('border-gray-300'); // Optional: reset original border
                }
            });
        });
    });
</script>
@endpush