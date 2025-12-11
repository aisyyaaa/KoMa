<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Penjual KoMa Market</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-8">

    {{-- CONTAINER UTAMA: max-w-5xl, min-h-[700px] --}}
    <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-[600px] lg:min-h-[700px]">
        
        {{-- LEFT SIDE: Welcome Section (Branding) --}}
        <div class="hidden lg:flex lg:w-1/3 bg-koma-accent text-white flex-col items-center justify-center p-8">
            <div class="text-center max-w-xs">
                <div class="mb-6">
                    <h1 class="text-4xl font-extrabold mb-2 tracking-wide">KoMa</h1>
                    <div class="h-1 w-12 bg-white mx-auto opacity-80 rounded-full"></div>
                </div>
                
                <h2 class="text-2xl font-semibold mb-4">Daftar Menjadi Penjual</h2>
                
                <p class="text-sm opacity-90 leading-relaxed mb-8">
                    Isi 4 langkah mudah untuk mulai berjualan. Verifikasi data Anda akan diproses dalam 1x24 jam.
                </p>

                <p class="text-sm font-light opacity-90">
                    Sudah punya akun? 
                    {{-- KOREKSI: route('seller.auth.login') sesuai definisi route terbaru --}}
                    <a href="{{ route('seller.auth.login') }}" class="font-bold hover:text-koma-primary-light transition">Masuk di sini</a>
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE: Register Form Container --}}
        <div class="w-full lg:w-2/3 flex flex-col items-center justify-start p-6 sm:p-8 lg:p-10 overflow-y-auto">
            <div class="w-full max-w-lg">
                
                {{-- HEADER FOR MOBILE/LG --}}
                <div class="text-center lg:text-left mb-6">
                    <h2 class="text-3xl font-extrabold text-koma-text-dark mb-1">Daftar Penjual</h2>
                    <p class="text-gray-600 text-sm">Lengkapi 4 langkah di bawah ini untuk membuka toko Anda.</p>
                </div>

                {{-- STEP INDICATOR (Dibuat lebih solid) --}}
                <div class="flex justify-between items-center mb-8 pb-1 border-b border-gray-100" id="stepIndicator">
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-koma-primary text-white rounded-full flex items-center justify-center text-xs font-bold transition duration-300 step-1">1</div>
                        <p class="text-xs text-gray-700 mt-2 font-medium text-center hidden sm:block">Toko</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 rounded-full step-line step-line-1 transition-colors duration-300"></div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-xs font-bold transition duration-300 step-2">2</div>
                        <p class="text-xs text-gray-700 mt-2 font-medium text-center hidden sm:block">PIC</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 rounded-full step-line step-line-2 transition-colors duration-300"></div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-xs font-bold transition duration-300 step-3">3</div>
                        <p class="text-xs text-gray-700 mt-2 font-medium text-center hidden sm:block">Alamat</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 rounded-full step-line step-line-3 transition-colors duration-300"></div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-xs font-bold transition duration-300 step-4">4</div>
                        <p class="text-xs text-gray-700 mt-2 font-medium text-center hidden sm:block">Akun & Dokumen</p>
                    </div>
                </div>

                {{-- ERROR ALERT (Dibuat lebih halus) --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm" role="alert">
                        <strong class="font-bold text-sm block">Ada kesalahan input</strong>
                        <ul class="mt-1 list-disc list-inside text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="registerForm" action="{{ route('seller.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- STEP 1: Informasi Toko --}}
                    <div class="step-content space-y-4" id="step-1">
                        <h3 class="text-lg font-semibold text-koma-primary border-b pb-2 mb-4">1. Informasi Toko</h3>
                        <div class="mb-4">
                            <label for="nama_toko" class="block text-xs font-medium text-gray-700 mb-1">
                                Nama Toko <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="nama_toko" 
                                id="nama_toko" 
                                value="{{ old('nama_toko') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                        focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                        @error('nama_toko') border-red-500 @enderror" 
                                placeholder="Contoh: Toko Buku KoMa Jaya" 
                                required>
                            @error('nama_toko') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="deskripsi_singkat" class="block text-xs font-medium text-gray-700 mb-1">
                                Deskripsi Singkat (Maks 100 Karakter)
                            </label>
                            <input 
                                type="text" 
                                name="deskripsi_singkat" 
                                id="deskripsi_singkat" 
                                value="{{ old('deskripsi_singkat') }}" 
                                maxlength="100"
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                        focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                        @error('deskripsi_singkat') border-red-500 @enderror"
                                placeholder="Contoh: Jual perlengkapan kuliah dan kos murah">
                            @error('deskripsi_singkat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- STEP 2: Data PIC --}}
                    <div class="step-content hidden space-y-4" id="step-2">
                        <h3 class="text-lg font-semibold text-koma-primary border-b pb-2 mb-4">2. Data Penanggung Jawab (PIC)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_pic" class="block text-xs font-medium text-gray-700 mb-1">
                                    Nama Lengkap PIC <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="nama_pic" 
                                    id="nama_pic" 
                                    value="{{ old('nama_pic') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('nama_pic') border-red-500 @enderror" 
                                    placeholder="Contoh: Budi Santoso" 
                                    required>
                                @error('nama_pic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="no_ktp_pic" class="block text-xs font-medium text-gray-700 mb-1">
                                    Nomor KTP (16 Digit) <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="no_ktp_pic" 
                                    id="no_ktp_pic" 
                                    value="{{ old('no_ktp_pic') }}" 
                                    maxlength="16" 
                                    inputmode="numeric" 
                                    pattern="\d{16}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('no_ktp_pic') border-red-500 @enderror" 
                                    placeholder="3302xxxxxxxxxxxx" 
                                    required>
                                <p id="ktp-feedback" class="text-red-500 text-xs mt-1 hidden">NIK harus 16 digit angka</p>
                                @error('no_ktp_pic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="no_hp_pic" class="block text-xs font-medium text-gray-700 mb-1">
                                    Nomor Telepon PIC <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    name="no_hp_pic" 
                                    id="no_hp_pic" 
                                    value="{{ old('no_hp_pic') }}" 
                                    maxlength="15" 
                                    inputmode="tel" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('no_hp_pic') border-red-500 @enderror" 
                                    placeholder="081234567890" 
                                    required>
                                <p id="phone-feedback" class="text-red-500 text-xs mt-1 hidden">Format telepon tidak valid</p>
                                @error('no_hp_pic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                                    Email PIC (Digunakan untuk Akun) <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    value="{{ old('email') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('email') border-red-500 @enderror" 
                                    placeholder="pic_koma@gmail.com" 
                                    required>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3: Alamat --}}
                    <div class="step-content hidden space-y-4" id="step-3">
                        <h3 class="text-lg font-semibold text-koma-primary border-b pb-2 mb-4">3. Alamat Lengkap PIC</h3>
                    
                        <div class="mb-4">
                            <label for="alamat_pic" class="block text-xs font-medium text-gray-700 mb-1">
                                Nama Jalan/Perumahan <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="alamat_pic" 
                                id="alamat_pic" 
                                value="{{ old('alamat_pic') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                        focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                        @error('alamat_pic') border-red-500 @enderror" 
                                placeholder="Contoh: Jl. Maju Makmur No. 12" 
                                required>
                            @error('alamat_pic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Grouping RT/RW --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="rt" class="block text-xs font-medium text-gray-700 mb-1">
                                    RT <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="rt" 
                                    id="rt" 
                                    value="{{ old('rt') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('rt') border-red-500 @enderror" 
                                    placeholder="005" 
                                    required>
                                @error('rt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="rw" class="block text-xs font-medium text-gray-700 mb-1">
                                    RW <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="rw" 
                                    id="rw" 
                                    value="{{ old('rw') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('rw') border-red-500 @enderror" 
                                    placeholder="002" 
                                    required>
                                @error('rw') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Grouping Kelurahan/Kecamatan --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nama_kelurahan" class="block text-xs font-medium text-gray-700 mb-1">
                                    Desa/Kelurahan <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="nama_kelurahan" 
                                    id="nama_kelurahan" 
                                    value="{{ old('nama_kelurahan') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('nama_kelurahan') border-red-500 @enderror" 
                                    placeholder="Mawar" 
                                    required>
                                @error('nama_kelurahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="nama_kecamatan" class="block text-xs font-medium text-gray-700 mb-1">
                                    Kecamatan <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="nama_kecamatan" 
                                    id="nama_kecamatan" 
                                    value="{{ old('nama_kecamatan') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('nama_kecamatan') border-red-500 @enderror" 
                                    placeholder="Melati" 
                                    required>
                                @error('nama_kecamatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Grouping Kota/Provinsi --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="kabupaten_kota" class="block text-xs font-medium text-gray-700 mb-1">
                                    Kota/Kabupaten <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="kabupaten_kota" 
                                    id="kabupaten_kota" 
                                    value="{{ old('kabupaten_kota') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('kabupaten_kota') border-red-500 @enderror" 
                                    placeholder="Jakarta Pusat" 
                                    required>
                                @error('kabupaten_kota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="propinsi" class="block text-xs font-medium text-gray-700 mb-1">
                                    Provinsi <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="propinsi" 
                                    id="propinsi" 
                                    value="{{ old('propinsi') }}" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('propinsi') border-red-500 @enderror" 
                                    placeholder="DKI Jakarta" 
                                    required>
                                @error('propinsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4: Dokumen Verifikasi & Akun Login --}}
                    <div class="step-content hidden space-y-4" id="step-4">
                        <h3 class="text-lg font-semibold text-koma-primary border-b pb-2 mb-4">4. Dokumen Verifikasi dan Akun Login</h3>
                        <p class="text-xs text-gray-500 mb-4">Pastikan dokumen jelas. Password ini akan digunakan untuk login.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="foto_pic" class="block text-xs font-medium text-gray-700 mb-1">
                                    Foto PIC (Max 2MB, JPG/PNG) <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="file" 
                                    name="foto_pic" 
                                    id="foto_pic" 
                                    accept="image/jpeg,image/png,image/jpg"
                                    class="w-full text-xs text-gray-500 pt-2 pb-1.5
                                            file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-gray-300 file:text-xs file:font-semibold 
                                            file:bg-gray-100 file:text-koma-primary hover:file:bg-gray-200 transition duration-150
                                            @error('foto_pic') file:border-red-500 @enderror" required>
                                @error('foto_pic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="file_ktp_pic" class="block text-xs font-medium text-gray-700 mb-1">
                                    Scan KTP (Max 5MB, PDF/JPG/PNG) <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="file" 
                                    name="file_ktp_pic" 
                                    id="file_ktp_pic" 
                                    accept="application/pdf,image/jpeg,image/png,image/jpg"
                                    class="w-full text-xs text-gray-500 pt-2 pb-1.5
                                            file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-gray-300 file:text-xs file:font-semibold 
                                            file:bg-gray-100 file:text-koma-primary hover:file:bg-gray-200 transition duration-150
                                            @error('file_ktp_pic') file:border-red-500 @enderror" required>
                                @error('file_ktp_pic') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        {{-- Password Section DIBUAT TERPISAH dan menonjol --}}
                        <div class="mt-6 p-4 bg-gray-100 rounded-lg border border-gray-200 shadow-sm space-y-3">
                            <div>
                                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                                    Password <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary
                                            @error('password') border-red-500 @enderror" 
                                    placeholder="Minimal 8 karakter" 
                                    required>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">
                                    Konfirmasi Password <span class="text-koma-danger">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                            focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-primary"
                                    placeholder="Ulangi password" 
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex gap-3 mt-8 pt-4 border-t border-gray-100" id="buttonContainer">
                        <button 
                            type="button" 
                            id="prevBtn"
                            onclick="changeStep(-1)"
                            class="w-1/3 py-2.5 px-4 border border-gray-300 text-gray-600 font-semibold rounded-lg 
                                    hover:bg-gray-100 transition duration-200 text-sm hidden">
                            ← Kembali
                        </button>
                        <a href="{{ route('katalog.index') }}" 
                            class="w-1/3 py-2.5 px-4 border border-gray-300 text-gray-600 font-semibold rounded-lg 
                                    hover:bg-gray-100 transition duration-200 text-center text-sm"
                            id="cancelBtn">
                            Batal
                        </a>
                        <button 
                            type="button" 
                            id="nextBtn"
                            onclick="changeStep(1)"
                            class="w-2/3 py-2.5 px-4 bg-koma-primary text-white font-semibold rounded-lg 
                                    shadow-md shadow-koma-primary/30 hover:bg-koma-danger transition duration-200 text-sm">
                            Lanjut →
                        </button>
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="w-2/3 py-2.5 px-4 bg-koma-primary text-white font-semibold rounded-lg 
                                    shadow-md shadow-koma-primary/30 hover:bg-koma-danger transition duration-200 text-sm hidden">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        let currentStep = 1;
        const totalSteps = 4;

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Show current step
            document.getElementById(`step-${step}`).classList.remove('hidden');
            
            // Update step indicator
            updateStepIndicator(step);
            
            // Update buttons
            updateButtons(step);
            
            const formContainer = document.querySelector('.w-full.max-w-5xl');
            if (formContainer) {
                formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function updateStepIndicator(step) {
            for (let i = 1; i <= totalSteps; i++) {
                const circle = document.querySelector(`.step-${i}`);
                const line = document.querySelector(`.step-line-${i}`);
                
                if (i <= step) {
                    // Active/Done State
                    circle.classList.remove('bg-gray-200', 'text-gray-500');
                    circle.classList.add('bg-koma-primary', 'text-white');
                    if (line) {
                        line.classList.remove('bg-gray-200');
                        line.classList.add('bg-koma-primary');
                    }
                } else {
                    // Inactive State
                    circle.classList.remove('bg-koma-primary', 'text-white');
                    circle.classList.add('bg-gray-200', 'text-gray-500');
                    if (line) {
                        line.classList.remove('bg-koma-primary');
                        line.classList.add('bg-gray-200');
                    }
                }
            }
        }

        function updateButtons(step) {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            // Reset widths
            prevBtn.classList.remove('w-full', 'w-1/3');
            nextBtn.classList.remove('w-full', 'w-2/3');
            submitBtn.classList.remove('w-full', 'w-2/3');
            cancelBtn.classList.remove('w-1/3');
            
            if (step === 1) {
                // Tampilan: Batal (1/3) | Lanjut (2/3)
                prevBtn.classList.add('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                cancelBtn.classList.remove('hidden');

                cancelBtn.classList.add('w-1/3');
                nextBtn.classList.add('w-2/3');
                
            } else if (step < totalSteps) {
                // Tampilan: Kembali (1/3) | Lanjut (2/3)
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');

                prevBtn.classList.add('w-1/3');
                nextBtn.classList.add('w-2/3');
            } else {
                // Tampilan: Kembali (1/3) | Daftar (2/3)
                prevBtn.classList.remove('hidden');
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
                cancelBtn.classList.add('hidden');
                
                prevBtn.classList.add('w-1/3');
                submitBtn.classList.add('w-2/3');
            }
        }

        function validateStep(step) {
            const requiredInputs = document.querySelectorAll(`#step-${step} input[required], #step-${step} textarea[required]`);
            let isValid = true;
            let firstInvalidInput = null;

            requiredInputs.forEach(input => {
                input.classList.remove('border-red-500'); // Reset validation style

                let isFieldValid = true;
                
                if (input.type === 'file') {
                    if (!input.files || input.files.length === 0) {
                        isFieldValid = false;
                    } 
                } else if (!input.value.trim()) {
                    isFieldValid = false;
                }
                
                if (!isFieldValid) {
                    input.classList.add('border-red-500');
                    isValid = false;
                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                }
            });
            
            if (step === 2) {
                const ktpValid = document.getElementById('ktp-feedback').classList.contains('hidden');
                const phoneValid = document.getElementById('phone-feedback').classList.contains('hidden');
                
                if (!ktpValid) isValid = false;
                if (!phoneValid) isValid = false;
            }
            
            if (step === 4) {
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('password_confirmation');
                
                let passwordMismatch = false;
                if (passwordInput.value !== confirmInput.value) {
                    passwordMismatch = true;
                    confirmInput.classList.add('border-red-500');
                    isValid = false;
                    if (!firstInvalidInput) firstInvalidInput = confirmInput;
                } else {
                    confirmInput.classList.remove('border-red-500');
                }

                if (passwordInput.value.length < 8 && passwordInput.required) {
                     passwordInput.classList.add('border-red-500');
                     isValid = false;
                     if (!firstInvalidInput && !passwordMismatch) firstInvalidInput = passwordInput;
                } else {
                     passwordInput.classList.remove('border-red-500');
                }
            }

            if (!isValid && firstInvalidInput) {
                firstInvalidInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            return isValid;
        }

        function changeStep(direction) {
            if (direction > 0 && !validateStep(currentStep)) {
                alert('Mohon lengkapi semua field yang wajib diisi dengan benar sebelum melanjutkan.');
                return;
            }
            
            const newStep = currentStep + direction;
            
            if (newStep >= 1 && newStep <= totalSteps) {
                currentStep = newStep;
                showStep(currentStep);
            }
        }

        document.addEventListener('input', function(e) {
            const input = e.target;
            if (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
                if ((input.required && input.value.trim()) || (input.type === 'file' && input.files.length > 0)) {
                    input.classList.remove('border-red-500');
                }
                
                if (input.id === 'password' || input.id === 'password_confirmation') {
                    const pass = document.getElementById('password');
                    const confirm = document.getElementById('password_confirmation');
                    if (pass.value && confirm.value && pass.value === confirm.value && pass.value.length >= 8) {
                         confirm.classList.remove('border-red-500');
                         pass.classList.remove('border-red-500');
                    } else if (confirm.value && pass.value !== confirm.value) {
                         confirm.classList.add('border-red-500');
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
             const hasError = document.querySelector('.border-red-500');
             if (hasError) {
                const errorStepContent = hasError.closest('.step-content');
                if (errorStepContent) {
                    const errorStepId = errorStepContent.id; 
                    currentStep = parseInt(errorStepId.split('-')[1]);
                    // Scroll to error after showing step
                    setTimeout(() => {
                        hasError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 100); 
                }
             }

            showStep(currentStep);
        });

        const ktpInput = document.getElementById('no_ktp_pic');
        const ktpFeedback = document.getElementById('ktp-feedback');
        const phoneInput = document.getElementById('no_hp_pic');
        const phoneFeedback = document.getElementById('phone-feedback');

        function validateKTP() {
            const value = ktpInput.value;
            const onlyDigits = /^\d*$/.test(value);
            ktpInput.classList.remove('border-red-500');

            if (value.length > 0) {
                if (!onlyDigits) {
                    ktpFeedback.textContent = "NIK hanya boleh berisi angka (0-9).";
                    ktpFeedback.classList.remove('hidden');
                    ktpInput.classList.add('border-red-500');
                } else if (value.length !== 16) {
                    ktpFeedback.textContent = `NIK harus 16 digit. Saat ini: ${value.length} digit.`;
                    ktpFeedback.classList.remove('hidden');
                    ktpInput.classList.add('border-red-500');
                } else {
                    ktpFeedback.classList.add('hidden');
                }
            } else if (ktpInput.required) {
                 ktpFeedback.classList.add('hidden');
            }
        }

        function validatePhone() {
            const value = phoneInput.value;
            const phoneRegex = /^08[\d]{8,13}$/; 
            const onlyDigits = /^\d*$/.test(value);
            phoneInput.classList.remove('border-red-500');
            
            if (value.length > 0) {
                if (!onlyDigits) {
                    phoneFeedback.textContent = "Nomor telepon hanya boleh berisi angka.";
                    phoneFeedback.classList.remove('hidden');
                    phoneInput.classList.add('border-red-500');
                } else if (value.length < 10 || value.length > 15) {
                    phoneFeedback.textContent = `Nomor telepon harus antara 10-15 digit. Saat ini: ${value.length} digit.`;
                    phoneFeedback.classList.remove('hidden');
                    phoneInput.classList.add('border-red-500');
                } 
                else if (value.length >= 10 && value.length <= 15 && !phoneRegex.test(value)) {
                    phoneFeedback.textContent = "Format telepon tidak valid (contoh: 081xxxxxxxx).";
                    phoneFeedback.classList.remove('hidden');
                    phoneInput.classList.add('border-red-500');
                } else {
                    phoneFeedback.classList.add('hidden');
                }
            } else if (phoneInput.required) {
                phoneFeedback.classList.add('hidden');
            }
        }

        ktpInput?.addEventListener('input', validateKTP);
        phoneInput?.addEventListener('input', validatePhone);

        ktpInput?.addEventListener('keypress', (event) => {
            if (event.key.length === 1 && !/\d/.test(event.key)) {
                event.preventDefault();
            }
        });
        
        phoneInput?.addEventListener('keypress', (event) => {
            if (event.key.length === 1 && !/\d/.test(event.key)) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>