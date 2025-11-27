<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Penjual KoMa Market</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-xl my-8">
        <h2 class="text-3xl font-bold text-koma-primary mb-6 border-b pb-2">Daftar Menjadi Penjual</h2>
        <p class="text-gray-600 mb-6">Lengkapi data di bawah ini untuk membuka toko Anda di KoMa Market.</p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">Oops! Ada kesalahan input:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('seller.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3 class="text-xl font-semibold text-koma-text-dark border-b mt-6 mb-4 pb-1">1. Informasi Toko</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="storeName" class="block text-sm font-medium text-gray-700">Nama Toko <span class="text-koma-danger">*</span></label>
                    <input type="text" name="storeName" id="storeName" value="{{ old('storeName') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('storeName') border-red-500 @enderror" placeholder="Contoh: Toko Buku KoMa Jaya" required>
                    @error('storeName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="storeDescription" class="block text-sm font-medium text-gray-700">Deskripsi Toko (Opsional)</label>
                    <input type="text" name="storeDescription" id="storeDescription" value="{{ old('storeDescription') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('storeDescription') border-red-500 @enderror" placeholder="Contoh: Jual perlengkapan kuliah dan kos murah">
                    @error('storeDescription') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <h3 class="text-xl font-semibold text-koma-text-dark border-b mt-4 mb-4 pb-1">2. Data Penanggung Jawab (PIC)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="picName" class="block text-sm font-medium text-gray-700">Nama Lengkap PIC <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picName" id="picName" value="{{ old('picName') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picName') border-red-500 @enderror" placeholder="Contoh: Budi Santoso" required>
                    @error('picName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picKtpNumber" class="block text-sm font-medium text-gray-700">Nomor KTP (NIK - 16 Digit) <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picKtpNumber" id="picKtpNumber" value="{{ old('picKtpNumber') }}" maxlength="16" inputmode="numeric" pattern="\d{16}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picKtpNumber') border-red-500 @enderror" placeholder="Contoh: 3302xxxxxxxxxxxx (16 angka)" required>
                    <p id="ktp-feedback" class="text-red-500 text-xs mt-1 hidden">NIK harus 16 digit angka (hanya angka).</p>
                    @error('picKtpNumber') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picPhone" class="block text-sm font-medium text-gray-700">Nomor Telepon PIC <span class="text-koma-danger">*</span></label>
                    <input type="tel" name="picPhone" id="picPhone" value="{{ old('picPhone') }}" maxlength="15" inputmode="tel" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picPhone') border-red-500 @enderror" placeholder="Contoh: 081234567890 (Max 15 digit)" required>
                    <p id="phone-feedback" class="text-red-500 text-xs mt-1 hidden">Nomor telepon tidak valid (contoh: 081xxxxxxxx atau +62...).</p>
                    @error('picPhone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picEmail" class="block text-sm font-medium text-gray-700">Email PIC <span class="text-koma-danger">*</span></label>
                    <input type="email" name="picEmail" id="picEmail" value="{{ old('picEmail') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picEmail') border-red-500 @enderror" placeholder="Contoh: pic_koma@gmail.com" required>
                    @error('picEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <h3 class="text-xl font-semibold text-koma-text-dark border-b mt-4 mb-4 pb-1">3. Alamat Lengkap PIC</h3>
            
            <div class="mb-4">
                <label for="picStreet" class="block text-sm font-medium text-gray-700">Nama Jalan/Perumahan <span class="text-koma-danger">*</span></label>
                <input type="text" name="picStreet" id="picStreet" value="{{ old('picStreet') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picStreet') border-red-500 @enderror" placeholder="Contoh: Jl. Maju Makmur No. 12" required>
                @error('picStreet') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="picRT" class="block text-sm font-medium text-gray-700">RT <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picRT" id="picRT" value="{{ old('picRT') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picRT') border-red-500 @enderror" placeholder="Contoh: 005" required>
                    @error('picRT') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picRW" class="block text-sm font-medium text-gray-700">RW <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picRW" id="picRW" value="{{ old('picRW') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picRW') border-red-500 @enderror" placeholder="Contoh: 002" required>
                    @error('picRW') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8"> 
                <div>
                    <label for="picVillage" class="block text-sm font-medium text-gray-700">Desa/Kelurahan <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picVillage" id="picVillage" value="{{ old('picVillage') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picVillage') border-red-500 @enderror" placeholder="Contoh: Mawar" required>
                    @error('picVillage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picDistrict" class="block text-sm font-medium text-gray-700">Kecamatan <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picDistrict" id="picDistrict" value="{{ old('picDistrict') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picDistrict') border-red-500 @enderror" placeholder="Contoh: Melati" required>
                    @error('picDistrict') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picCity" class="block text-sm font-medium text-gray-700">Kota/Kabupaten <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picCity" id="picCity" value="{{ old('picCity') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picCity') border-red-500 @enderror" placeholder="Contoh: Jakarta Pusat" required>
                    @error('picCity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picProvince" class="block text-sm font-medium text-gray-700">Provinsi <span class="text-koma-danger">*</span></label>
                    <input type="text" name="picProvince" id="picProvince" value="{{ old('picProvince') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('picProvince') border-red-500 @enderror" placeholder="Contoh: DKI Jakarta" required>
                    @error('picProvince') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div> 
            
            <h3 class="text-xl font-semibold text-koma-text-dark border-b mt-4 mb-4 pb-1">4. Dokumen Verifikasi (Opsional)</h3>
            <p class="text-sm text-gray-500 mb-4">Dokumen diperlukan untuk verifikasi akun, tetapi dapat di-upload nanti.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="picPhoto" class="block text-sm font-medium text-gray-700">Foto PIC (Max 2MB, JPG/PNG) <span class="text-koma-danger">*</span></label>
                    <input type="file" name="picPhoto" id="picPhoto" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-koma-hover-light file:text-koma-accent hover:file:bg-koma-bg-light @error('picPhoto') border-red-500 @enderror">
                    @error('picPhoto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="picKtpFile" class="block text-sm font-medium text-gray-700">File Scan KTP (Max 5MB, JPG/PNG/PDF) <span class="text-koma-danger">*</span></label>
                    <input type="file" name="picKtpFile" id="picKtpFile" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-koma-hover-light file:text-koma-accent hover:file:bg-koma-bg-light @error('picKtpFile') border-red-500 @enderror">
                    @error('picKtpFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                
                <a href="{{ route('katalog.index') }}" 
                    class="w-1/4 py-3 px-4 border border-gray-300 bg-gray-50 text-koma-text-dark font-semibold rounded-md shadow-md 
                           hover:bg-koma-accent hover:text-white text-center transition duration-200">
                    Batal
                </a>
                
                <button type="submit" class="w-1/4 py-3 px-4 bg-koma-primary text-white font-semibold rounded-md shadow-md hover:bg-koma-danger transition duration-200">
                    Daftar dan Ajukan Verifikasi
                </button>
            </div>
        </form>
    </div>

   <script>
        const ktpInput = document.getElementById('picKtpNumber');
        const ktpFeedback = document.getElementById('ktp-feedback');
        const phoneInput = document.getElementById('picPhone');
        const phoneFeedback = document.getElementById('phone-feedback');

        // --- Validasi NIK (Real-time) ---
        function validateKTP() {
            const value = ktpInput.value;
            const onlyDigits = /^\d*$/.test(value);

            if (value.length > 0 && !onlyDigits) {
                ktpFeedback.textContent = "NIK hanya boleh berisi angka (0-9).";
                ktpFeedback.classList.remove('hidden');
                ktpInput.classList.add('border-red-500');
            } else if (value.length > 0 && value.length !== 16) {
                ktpFeedback.textContent = `NIK harus 16 digit. Saat ini: ${value.length}`;
                ktpFeedback.classList.remove('hidden');
                ktpInput.classList.add('border-red-500');
            } else {
                ktpFeedback.classList.add('hidden');
                ktpInput.classList.remove('border-red-500');
            }
        }

        // --- Validasi Telepon (Real-time) - REVISI AKHIR OPTIMAL ---
        function validatePhone() {
            const value = phoneInput.value;
            // Regex untuk format nomor telepon Indonesia (min 10, max 15, dimulai 08 atau +628)
            const phoneRegex = /^(08|\+628)[\d]{8,12}$/;
            
            // Cek jika input diisi
            if (value.length > 0) {
                
                // KONDISI 1: Terlalu pendek (kurang dari 10 digit)
                if (value.length < 10) {
                    phoneFeedback.textContent = `Nomor telepon harus minimal 10 digit. Saat ini: ${value.length}`;
                    phoneFeedback.classList.remove('hidden');
                    phoneInput.classList.remove('border-red-500'); 
                } 
                // KONDISI 2: Sudah penuh (tepat 15 digit)
                else if (value.length === 15) {
                    // Saat sudah penuh, kita hanya perlu cek apakah format dasarnya (diawali 0 atau +62) sudah benar.
                    if (phoneRegex.test(value)) {
                        // Jika format benar, hilangkan semua warning. Stop input sudah diurus HTML.
                        phoneFeedback.classList.add('hidden');
                        phoneInput.classList.remove('border-red-500');
                    } 
                }
                // KONDISI 3: Panjang wajar (10-14 digit) TAPI formatnya salah
                else if (!phoneRegex.test(value)) {
                    phoneFeedback.textContent = "Format telepon tidak valid (contoh: 081xxxxxxxx atau +62...).";
                    phoneFeedback.classList.remove('hidden');
                    phoneInput.classList.add('border-red-500');
                } 
                // KONDISI 4: Panjang wajar DAN formatnya valid
                else {
                    phoneFeedback.classList.add('hidden');
                    phoneInput.classList.remove('border-red-500');
                }
            } else {
                // Input kosong
                phoneFeedback.classList.add('hidden');
                phoneInput.classList.remove('border-red-500');
            }
        }

        // Event Listeners
        ktpInput.addEventListener('input', validateKTP);
        phoneInput.addEventListener('input', validatePhone);

        ktpInput.addEventListener('keypress', (event) => {
            if (event.key.length === 1 && !/\d/.test(event.key)) {
                event.preventDefault();
            }
        });
        
    </script>
</body>
</html>