<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - KoMa Market</title>
    @vite('resources/css/app.css') 
    
    <style>
        /* Gaya untuk Star Rating (tetap sama) */
        .star-rating input { display: none; }
        .star-rating label { font-size: 24px; color: #ccc; cursor: pointer; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #ffc107; }
        .star-rating { direction: rtl; }
        /* Gaya untuk tombol disable */
        .btn-disabled { opacity: 0.6; cursor: not-allowed; }
        .field-warning { color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; }
        
        /* FIX KRITIS: Gaya untuk Thumbnail Slider Horizontal */
        .thumbnail-container {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding-bottom: 10px; /* Ruang untuk scrollbar */
            /* Menyembunyikan scrollbar bawaan untuk tampilan yang lebih bersih (opsional) */
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        .thumbnail-container::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        .thumbnail-item {
            flex-shrink: 0; /* Penting agar item tidak mengecil */
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e5e7eb; /* Border abu-abu default */
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .thumbnail-item.active {
            border-color: #f6ad55; /* Warna aksen aktif (misal: orange/yellow) */
        }
        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50 text-koma-text-dark">

    {{-- HEADER (Minimalis) --}}
    <header class="shadow-md bg-white">
        <div class="bg-koma-accent w-full py-2"></div>
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('katalog.index') }}" class="text-2xl font-bold text-koma-primary hover:text-koma-danger transition duration-150">
                ← Kembali ke Katalog
            </a>
            <div class="text-lg font-medium">Detail Produk</div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="container mx-auto px-4 py-8">
        
        {{-- Pesan Status --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-xl shadow-lg">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Kolom Kiri: GAMBAR PRODUK (FIX GALERI THUMBNAIL) --}}
                <div class="col-span-1">
                    @php
                        // Gabungkan Gambar Utama dan Gambar Tambahan
                        $allImages = array_merge(
                            [$product->primary_image_url ?? asset('images/default-product.png')], 
                            $product->additional_image_urls ?? []
                        );
                    @endphp

                    {{-- 1. Gambar Utama Besar (Display Gambar yang Aktif) --}}
                    <div class="relative w-full h-96 mb-3">
                        <img id="main-product-image"
                            src="{{ $allImages[0] ?? asset('images/default-product.png') }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-full object-cover rounded-lg shadow-md"
                        >
                        
                        {{-- Badge Kategori --}}
                        @if($product->category)
                            <span class="absolute top-3 left-3 bg-koma-primary text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>

                    {{-- 2. Thumbnail Slider Horizontal (Jika lebih dari 1 gambar) --}}
                    @if (count($allImages) > 1)
                        <div class="thumbnail-container">
                            @foreach ($allImages as $index => $imageUrl)
                            <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" 
                                 data-img-url="{{ $imageUrl }}" 
                                 onclick="changeMainImage(this, '{{ $imageUrl }}')">
                                <img src="{{ $imageUrl }}" alt="Thumb {{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Kolom Tengah: Detail Produk & Harga --}}
                <div class="col-span-2">
                    <h1 class="text-4xl font-extrabold text-koma-primary mb-2">{{ $product->name }}</h1>
                    
                    {{-- Rating Rata-Rata (SRS-MartPlace-04) --}}
                    <div class="flex items-center mb-4">
                        @php
                            $avgRating = $product->average_rating ?? $product->reviews_avg_rating ?? 0;
                            $reviewCount = $product->reviews_count ?? $product->reviews->count();
                            $rating = round($avgRating);
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.02l6.56-.955L10 0l2.953 6.065 6.56.955-4.758 4.525 1.123 6.545z"></path></svg>
                        @endfor
                        <span class="ml-2 text-2xl font-bold text-gray-800">{{ number_format($avgRating, 1) }}</span>
                        <span class="ml-2 text-gray-500">({{ $reviewCount }} ulasan)</span>
                    </div>

                    {{-- Harga --}}
                    <div class="my-4 p-4 bg-koma-bg-light rounded-lg">
                        @if ($product->discount_price > 0 && $product->discount_price < $product->price)
                            <p class="text-sm text-gray-500 line-through">Harga Normal: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-4xl font-extrabold text-koma-danger">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                        @else
                            <p class="text-4xl font-extrabold text-koma-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    
                    {{-- Informasi Pengiriman --}}
                    <div class="mb-6 p-4 border rounded-lg">
                        <h3 class="text-xl font-semibold mb-3 border-b pb-1 text-koma-primary">Pengiriman</h3>
                        <div class="space-y-2 text-gray-700">
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2 text-koma-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                <strong>Dikirim dari:</strong> <span class="ml-2">{{ $product->shipment_origin_city ?? 'Lokasi Tidak Ditetapkan' }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2 text-koma-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M10.496 11.332L9.497 12.569a1 1 0 01-1.424 0l-.295-.316a1 1 0 00-1.424 0l-2.5 2.5a1 1 0 000 1.414l.295.316a1 1 0 010 1.424l-1.5 1.5a1 1 0 000 1.414l.295.316a1 1 0 010 1.424l-2.5 2.5a1 1 0 01-1.414 0l-2.5-2.5a1 1 0 010-1.414l1.5-1.5a1 1 0 000-1.424l-.316-.295a1 1 0 010-1.424l2.5-2.5a1 1 0 001.414 0l.316.295a1 1 0 011.424 0l.999-1.237a1 1 0 00-1.424-1.424z" clip-rule="evenodd"></path></svg>
                                <strong>Ongkir Dasar:</strong> <span class="ml-2 font-bold">Rp {{ number_format($product->base_shipping_cost ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>


                    {{-- Deskripsi --}}
                    <h3 class="text-xl font-semibold mt-6 mb-2 border-b pb-1">Deskripsi Produk</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $product->description }}</p>

                    {{-- Detail Atribut --}}
                    <h3 class="text-xl font-semibold mt-6 mb-2 border-b pb-1">Detail</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-700">
                        <div><strong class="font-bold">Kondisi:</strong> {{ $product->condition_label ?? $product->condition }}</div>
                        <div><strong class="font-bold">Kategori:</strong> {{ $product->category->name ?? 'N/A' }}</div>
                        <div><strong class="font-bold">Stok Tersedia:</strong> {{ number_format($product->stock, 0, ',', '.') }}</div>
                        <div><strong class="font-bold">Merek:</strong> {{ $product->brand ?? 'Tidak ada' }}</div>
                        <div><strong class="font-bold">Berat:</strong> {{ $product->weight }} gram</div>
                        <div><strong class="font-bold">Garansi:</strong> {{ $product->warranty ? $product->warranty . ' bulan' : 'Tidak ada' }}</div>
                    </div>

                    {{-- Informasi Toko --}}
                    <h3 class="text-xl font-semibold mt-6 mb-2 border-b pb-1">Informasi Penjual</h3>
                    <div class="flex items-center space-x-3 text-lg">
                        <svg class="w-6 h-6 text-koma-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M18.364 17.364A9 9 0 0112 21.996c-1.574 0-3.085-.36-4.428-1.043L3 21l1.043-4.428C3.36 15.085 3 13.574 3 12A9 9 0 1118.364 17.364zM12 18a6 6 0 00-6-6h-2c0 3.86 3.14 7 7 7v-2z"></path></svg>
                        <span class="font-bold text-koma-accent">{{ $product->seller->store_name ?? 'Toko Tidak Ditemukan' }}</span>
                    </div>
                    <p class="text-sm text-gray-500 ml-9">
                        Lokasi: {{ $product->seller->city ?? 'N/A' }}, {{ $product->seller->province ?? 'N/A' }}
                    </p>

                </div>
            </div> {{-- End grid product info --}}

        </div>

        {{-- BAGIAN BAWAH: REVIEW DAN FORMULIR --}}
        <div id="reviews-section" class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI (md:col-span-2): DAFTAR REVIEW --}}
            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold mb-6 border-b pb-2">Ulasan Pembeli ({{ $product->reviews->count() }})</h2>

                @forelse ($product->reviews->sortByDesc('reviewed_at') as $review)
                    <div class="border-b pb-4 mb-4">
                        {{-- Bintang Ulasan --}}
                        <div class="flex items-center mb-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.487 7.02l6.56-.955L10 0l2.953 6.065 6.56.955-4.758 4.525 1.123 6.545z"></path></svg>
                            @endfor
                            <span class="ml-3 text-sm font-semibold">{{ $review->visitor_name }}</span>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-2">
                            Dari <strong>{{ $review->province ?? 'Lokasi Tidak Diketahui' }}</strong> 
                        </p>
                        
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</p>
                @endforelse
            </div>

            {{-- KOLOM KANAN (md:col-span-1): FORM KOMENTAR & RATING (SRS-MartPlace-06) --}}
            <div class="md:col-span-1 bg-koma-bg-light p-6 rounded-xl shadow-lg h-fit sticky top-4">
                <h3 class="text-xl font-bold mb-4 border-b pb-2">Berikan Ulasan Anda</h3>

                <form id="reviewForm" method="POST" action="{{ route('katalog.review.store', $product) }}">
                    @csrf
                    
                    {{-- Input Rating (Skala 1-5) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Rating Anda</label>
                        <div class="star-rating flex justify-end">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                <label for="star{{$i}}" title="{{ $i }} bintang">★</label>
                            @endfor
                        </div>
                        <p id="ratingWarning" class="field-warning" style="{{ old('rating') ? 'display: none;' : 'display: block;' }}">Rating wajib diisi.</p>
                        @error('rating') <p class="text-red-500 text-sm mt-1">Rating wajib diisi.</p> @enderror
                    </div>

                    {{-- Input Komentar --}}
                    <div class="mb-4">
                        <label for="comment" class="block text-gray-700 font-semibold mb-2">Komentar</label>
                        <textarea name="comment" id="comment" rows="3" required
                                     class="w-full p-2 border rounded-md @error('comment') border-red-500 @enderror"
                                     placeholder="Tulis ulasan Anda di sini..." oninput="validateForm()">{{ old('comment') }}</textarea>
                        <p id="commentWarning" class="field-warning" style="display: none;">Komentar wajib diisi.</p>
                        @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Data Pengunjung --}}
                    <h4 class="text-md font-semibold mt-4 mb-3 border-t pt-2">Informasi Kontak:</h4>

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="visitor_name" class="block text-sm text-gray-700 mb-1">Nama Anda</label>
                        <input type="text" name="visitor_name" id="visitor_name" required 
                               class="w-full p-2 border rounded-md text-sm @error('visitor_name') border-red-500 @enderror"
                               value="{{ old('visitor_name') }}" oninput="validateForm()">
                        <p id="nameWarning" class="field-warning" style="display: none;">Nama wajib diisi.</p>
                        @error('visitor_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nomor HP --}}
                    <div class="mb-3">
                        <label for="visitor_phone" class="block text-sm text-gray-700 mb-1">Nomor HP</label>
                        <input type="tel" name="visitor_phone" id="visitor_phone" 
                               required 
                               maxlength="15" 
                               inputmode="numeric" 
                               class="w-full p-2 border rounded-md text-sm @error('visitor_phone') border-red-500 @enderror"
                               value="{{ old('visitor_phone') }}" oninput="validateForm()">
                        <p id="phoneWarning" class="field-warning" style="display: none;">Nomor HP wajib diisi.</p>
                        @error('visitor_phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal 15 digit (misal: 0812xxxx atau +62812xxxx).</p>
                    </div>
                    
                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="visitor_email" class="block text-sm text-gray-700 mb-1">Email (Untuk Notifikasi)</label>
                        <input type="email" name="visitor_email" id="visitor_email" required 
                               class="w-full p-2 border rounded-md text-sm @error('visitor_email') border-red-500 @enderror"
                               value="{{ old('visitor_email') }}" oninput="validateForm()">
                        <p id="emailWarning" class="field-warning" style="display: none;">Email wajib diisi.</p>
                        @error('visitor_email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    {{-- Provinsi --}}
                    <div class="mb-4">
                        <label for="province" class="block text-sm text-gray-700 mb-1">Provinsi Asal</label>
                        <select name="province" id="province" required 
                                 class="w-full p-2 border rounded-md text-sm @error('province') border-red-500 @enderror" onchange="validateForm()">
                            <option value="">-- Pilih Provinsi --</option>
                            @php
                                $provinces = ['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Sumatera Utara', 'Sulawesi Selatan', 'Bali'];
                            @endphp
                            @foreach ($provinces as $p)
                                <option value="{{ $p }}" {{ old('province') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                        <p id="provinceWarning" class="field-warning" style="display: none;">Provinsi wajib diisi.</p>
                        @error('province') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" id="submitButton" disabled
                            class="w-full mt-4 bg-koma-accent text-white font-bold py-2 rounded-md transition duration-200 btn-disabled">
                        Kirim Ulasan
                    </button>
                </form>
            </div> {{-- End Form Review --}}

        </div> {{-- End Grid Detail Produk --}}
        
    </main>
    
    {{-- FOOTER --}}
    <footer class="mt-10 py-6 bg-koma-accent text-white text-center text-sm">
        &copy; 2025 KoMa Market. Koperasi Mahasiswa. All Rights Reserved.
    </footer>

    {{-- SCRIPT LOGIC GALERI DAN VALIDASI --}}
    <script>
        // Logika untuk mengubah gambar utama saat thumbnail diklik
        function changeMainImage(clickedThumb, imageUrl) {
            const mainImage = document.getElementById('main-product-image');
            const thumbs = document.querySelectorAll('.thumbnail-item');

            // 1. Update gambar utama
            mainImage.src = imageUrl;

            // 2. Update kelas aktif pada thumbnail
            thumbs.forEach(thumb => thumb.classList.remove('active'));
            clickedThumb.classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // --- LOGIKA FORM REVIEW (Tetap sama) ---
            const form = document.getElementById('reviewForm');
            const submitButton = document.getElementById('submitButton');
            
            const requiredInputs = [
                document.getElementById('visitor_name'),
                document.getElementById('visitor_phone'),
                document.getElementById('visitor_email'),
                document.getElementById('province'),
                document.getElementById('comment'), 
            ];
            
            const ratingInputs = form.querySelectorAll('input[name="rating"]');

            const warningMap = {
                'visitor_name': document.getElementById('nameWarning'),
                'visitor_phone': document.getElementById('phoneWarning'),
                'visitor_email': document.getElementById('emailWarning'),
                'province': document.getElementById('provinceWarning'),
                'comment': document.getElementById('commentWarning'),
                'rating': document.getElementById('ratingWarning'),
            };

            // Fungsi utama untuk validasi
            window.validateForm = function() {
                let isFormValid = true;

                // 1. Cek Input Teks/Select/Textarea Wajib
                requiredInputs.forEach(input => {
                    const warningElement = warningMap[input.id];
                    
                    let isInputValid = (input.tagName === 'SELECT' && input.value.trim() === '') ? false : input.value.trim() !== '';

                    if (!isInputValid) {
                        isFormValid = false;
                        if (warningElement) warningElement.style.display = 'block';
                    } else {
                        if (warningElement) warningElement.style.display = 'none';
                    }
                });

                // 2. Cek Rating (Radio Buttons)
                let isRatingChecked = false;
                ratingInputs.forEach(radio => {
                    if (radio.checked) {
                        isRatingChecked = true;
                    }
                });
                
                const ratingWarningElement = warningMap['rating'];
                if (!isRatingChecked) {
                    isFormValid = false;
                    if (ratingWarningElement) ratingWarningElement.style.display = 'block';
                } else {
                    if (ratingWarningElement) ratingWarningElement.style.display = 'none';
                }

                // 3. Atur Status Tombol
                if (isFormValid) {
                    submitButton.removeAttribute('disabled');
                    submitButton.classList.remove('btn-disabled');
                    submitButton.classList.add('hover:bg-koma-primary');
                } else {
                    submitButton.setAttribute('disabled', 'disabled');
                    submitButton.classList.add('btn-disabled');
                    submitButton.classList.remove('hover:bg-koma-primary');
                }
            }

            // Tambahkan event listener pada semua input rating
            ratingInputs.forEach(radio => {
                radio.addEventListener('change', validateForm);
            });
            
            // Tambahkan event listener pada semua input wajib (kecuali rating yang sudah di atas)
            requiredInputs.forEach(input => {
                input.addEventListener('input', validateForm);
                input.addEventListener('change', validateForm); // Untuk select
            });


            // Jalankan validasi saat halaman dimuat (untuk old() values)
            validateForm();
        });
    </script>
</body>
</html>