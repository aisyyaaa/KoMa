<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Penjual KoMa Market</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-4">

    <div class="w-full max-w-6xl h-auto bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        
        {{-- LEFT SIDE: Welcome Section (Blue) - Diperkecil --}}
        <div class="hidden lg:flex lg:w-1/3 bg-koma-accent flex-col items-center justify-center p-6 overflow-y-auto">
            <div class="text-center text-white max-w-xs">
                <div class="mb-4">
                    <h1 class="text-4xl font-extrabold mb-2">KoMa</h1>
                    <div class="h-1 w-16 bg-white mx-auto"></div>
                </div>
                
                <h2 class="text-xl font-bold mb-3">Mulai Berjualan</h2>
                
                <p class="text-xs leading-relaxed mb-4">
                    Bergabunglah dengan ribuan penjual di KoMa Market dan kembangkan bisnis Anda sekarang.
                </p>

                <p class="text-xs opacity-90">
                    Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE: Register Form - Diperluas --}}
        <div class="w-full lg:w-2/3 flex flex-col items-center justify-start p-6 lg:p-8 overflow-y-auto">
            <div class="w-full max-w-xl">
                <div class="lg:hidden text-center mb-4">
                    <h1 class="text-3xl font-bold text-koma-primary mb-1">KoMa</h1>
                    <h2 class="text-lg font-bold text-koma-text-dark">Daftar Penjual</h2>
                </div>

                <div class="hidden lg:block mb-6">
                    <h2 class="text-xl font-bold text-koma-text-dark">Daftar Menjadi Penjual</h2>
                    <p class="text-gray-600 text-xs mt-1">Lengkapi semua data untuk membuka toko Anda</p>
                </div>

                {{-- STEP INDICATOR --}}
                <div class="flex justify-between items-center mb-6" id="stepIndicator">
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-koma-primary text-white rounded-full flex items-center justify-center text-xs font-bold step-1">1</div>
                        <p class="text-xs text-gray-700 mt-1 text-center">Toko</p>
                    </div>
                    <div class="flex-1 h-0.5 bg-koma-bg-light mx-1 step-line step-line-1"></div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-koma-bg-light text-koma-text-dark rounded-full flex items-center justify-center text-xs font-bold step-2">2</div>
                        <p class="text-xs text-gray-700 mt-1 text-center">PIC</p>
                    </div>
                    <div class="flex-1 h-0.5 bg-koma-bg-light mx-1 step-line step-line-2"></div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-koma-bg-light text-koma-text-dark rounded-full flex items-center justify-center text-xs font-bold step-3">3</div>
                        <p class="text-xs text-gray-700 mt-1 text-center">Alamat</p>
                    </div>
                    <div class="flex-1 h-0.5 bg-koma-bg-light mx-1 step-line step-line-3"></div>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="step-circle w-8 h-8 bg-koma-bg-light text-koma-text-dark rounded-full flex items-center justify-center text-xs font-bold step-4">4</div>
                        <p class="text-xs text-gray-700 mt-1 text-center">Dokumen</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-lg mb-4 text-sm" role="alert">
                        <strong class="font-bold">Ada kesalahan input</strong>
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
                    <div class="step-content" id="step-1">
                        <h3 class="text-sm font-semibold text-koma-text-dark border-b pb-2 mb-3">1. Informasi Toko</h3>
                    <div class="mb-4">
                        <label for="store_name" class="block text-xs font-medium text-gray-700 mb-1">
                            Nama Toko <span class="text-koma-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="store_name" 
                            id="store_name" 
                            value="{{ old('store_name') }}" 
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                   @error('store_name') border-red-500 @enderror" 
                            placeholder="Contoh: Toko Buku KoMa Jaya" 
                            required>
                        @error('store_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="store_description" class="block text-xs font-medium text-gray-700 mb-1">
                            Deskripsi Toko (Opsional)
                        </label>
                        <input 
                            type="text" 
                            name="store_description" 
                            id="store_description" 
                            value="{{ old('store_description') }}" 
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                   @error('store_description') border-red-500 @enderror"
                            placeholder="Contoh: Jual perlengkapan kuliah dan kos murah">
                        @error('store_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    </div>

                    {{-- STEP 2: Data PIC --}}
                    <div class="step-content hidden" id="step-2">
                        <h3 class="text-sm font-semibold text-koma-text-dark border-b pb-2 mb-3">2. Data Penanggung Jawab (PIC)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_name" class="block text-xs font-medium text-gray-700 mb-1">
                                Nama Lengkap PIC <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_name" 
                                id="pic_name" 
                                value="{{ old('pic_name') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_name') border-red-500 @enderror" 
                                placeholder="Contoh: Budi Santoso" 
                                required>
                            @error('pic_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pic_ktp_number" class="block text-xs font-medium text-gray-700 mb-1">
                                Nomor KTP (16 Digit) <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_ktp_number" 
                                id="pic_ktp_number" 
                                value="{{ old('pic_ktp_number') }}" 
                                maxlength="16" 
                                inputmode="numeric" 
                                pattern="\d{16}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_ktp_number') border-red-500 @enderror" 
                                placeholder="3302xxxxxxxxxxxx" 
                                required>
                            <p id="ktp-feedback" class="text-red-500 text-xs mt-1 hidden">NIK harus 16 digit angka</p>
                            @error('pic_ktp_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pic_phone" class="block text-xs font-medium text-gray-700 mb-1">
                                Nomor Telepon PIC <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="tel" 
                                name="pic_phone" 
                                id="pic_phone" 
                                value="{{ old('pic_phone') }}" 
                                maxlength="15" 
                                inputmode="tel" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_phone') border-red-500 @enderror" 
                                placeholder="081234567890" 
                                required>
                            <p id="phone-feedback" class="text-red-500 text-xs mt-1 hidden">Format telepon tidak valid</p>
                            @error('pic_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pic_email" class="block text-xs font-medium text-gray-700 mb-1">
                                Email PIC <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="pic_email" 
                                id="pic_email" 
                                value="{{ old('pic_email') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_email') border-red-500 @enderror" 
                                placeholder="pic_koma@gmail.com" 
                                required>
                            @error('pic_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    </div>

                    {{-- STEP 3: Alamat --}}
                    <div class="step-content hidden" id="step-3">
                        <h3 class="text-sm font-semibold text-koma-text-dark border-b pb-2 mb-3">3. Alamat Lengkap PIC</h3>
                    
                    <div class="mb-4">
                        <label for="pic_street" class="block text-xs font-medium text-gray-700 mb-1">
                            Nama Jalan/Perumahan <span class="text-koma-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="pic_street" 
                            id="pic_street" 
                            value="{{ old('pic_street') }}" 
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                   @error('pic_street') border-red-500 @enderror" 
                            placeholder="Contoh: Jl. Maju Makmur No. 12" 
                            required>
                        @error('pic_street') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="pic_rt" class="block text-xs font-medium text-gray-700 mb-1">
                                RT <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_rt" 
                                id="pic_rt" 
                                value="{{ old('pic_rt') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_rt') border-red-500 @enderror" 
                                placeholder="005" 
                                required>
                            @error('pic_rt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pic_rw" class="block text-xs font-medium text-gray-700 mb-1">
                                RW <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_rw" 
                                id="pic_rw" 
                                value="{{ old('pic_rw') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_rw') border-red-500 @enderror" 
                                placeholder="002" 
                                required>
                            @error('pic_rw') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="pic_village" class="block text-xs font-medium text-gray-700 mb-1">
                                Desa/Kelurahan <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_village" 
                                id="pic_village" 
                                value="{{ old('pic_village') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_village') border-red-500 @enderror" 
                                placeholder="Mawar" 
                                required>
                            @error('pic_village') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pic_district" class="block text-xs font-medium text-gray-700 mb-1">
                                Kecamatan <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_district" 
                                id="pic_district" 
                                value="{{ old('pic_district') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_district') border-red-500 @enderror" 
                                placeholder="Melati" 
                                required>
                            @error('pic_district') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="pic_city" class="block text-xs font-medium text-gray-700 mb-1">
                                Kota/Kabupaten <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_city" 
                                id="pic_city" 
                                value="{{ old('pic_city') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_city') border-red-500 @enderror" 
                                placeholder="Jakarta Pusat" 
                                required>
                            @error('pic_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pic_province" class="block text-xs font-medium text-gray-700 mb-1">
                                Provinsi <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="pic_province" 
                                id="pic_province" 
                                value="{{ old('pic_province') }}" 
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('pic_province') border-red-500 @enderror" 
                                placeholder="DKI Jakarta" 
                                required>
                            @error('pic_province') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    </div>

                    {{-- STEP 4: Dokumen Verifikasi --}}
                    <div class="step-content hidden" id="step-4">
                        <h3 class="text-sm font-semibold text-koma-text-dark border-b pb-2 mb-3">4. Dokumen Verifikasi</h3>
                    <p class="text-xs text-gray-500 mb-4">Dokumen dapat di-upload nanti jika belum tersedia.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_photo" class="block text-xs font-medium text-gray-700 mb-1">
                                Foto PIC (Max 2MB) <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="pic_photo" 
                                id="pic_photo" 
                                class="w-full text-xs text-gray-500 
                                       file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold 
                                       file:bg-koma-hover-light file:text-koma-accent hover:file:bg-koma-bg-light
                                       @error('pic_photo') border-red-500 @enderror">
                            @error('pic_photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pic_ktp_file" class="block text-xs font-medium text-gray-700 mb-1">
                                Scan KTP (Max 5MB) <span class="text-koma-danger">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="pic_ktp_file" 
                                id="pic_ktp_file" 
                                class="w-full text-xs text-gray-500 
                                       file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold 
                                       file:bg-koma-hover-light file:text-koma-accent hover:file:bg-koma-bg-light
                                       @error('pic_ktp_file') border-red-500 @enderror">
                            @error('pic_ktp_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                            Password <span class="text-koma-danger">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                   @error('password') border-red-500 @enderror" 
                            placeholder="Minimal 6 karakter" 
                            required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1 mt-3">
                            Konfirmasi Password <span class="text-koma-danger">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent"
                            placeholder="Ulangi password" 
                            required>
                    </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex gap-3 mt-6" id="buttonContainer">
                        <button 
                            type="button" 
                            id="prevBtn"
                            onclick="changeStep(-1)"
                            class="flex-1 py-2.5 px-4 border-2 border-gray-300 text-gray-600 font-semibold rounded-lg 
                                   hover:bg-gray-50 transition duration-200 text-sm hidden">
                            ← Kembali
                        </button>
                        <a href="{{ route('landingpage.index') }}" 
                            class="flex-1 py-2.5 px-4 border-2 border-gray-300 text-gray-600 font-semibold rounded-lg 
                                   hover:bg-gray-50 transition duration-200 text-center text-sm"
                            id="cancelBtn">
                            Batal
                        </a>
                        <button 
                            type="button" 
                            id="nextBtn"
                            onclick="changeStep(1)"
                            class="flex-1 py-2.5 px-4 bg-koma-primary text-white font-semibold rounded-lg 
                                   shadow-md hover:bg-koma-danger transition duration-200 text-sm">
                            Lanjut →
                        </button>
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="flex-1 py-2.5 px-4 bg-koma-primary text-white font-semibold rounded-lg 
                                   shadow-md hover:bg-koma-danger transition duration-200 text-sm hidden">
                            Daftar
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
            
            // Scroll to top
            document.querySelector('.step-content:not(.hidden)')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function updateStepIndicator(step) {
            // Update circles and lines
            for (let i = 1; i <= totalSteps; i++) {
                const circle = document.querySelector(`.step-${i}`);
                const line = document.querySelector(`.step-line-${i}`);
                
                if (i <= step) {
                    circle.classList.remove('bg-koma-bg-light', 'text-koma-text-dark');
                    circle.classList.add('bg-koma-primary', 'text-white');
                    if (line) {
                        line.classList.remove('bg-koma-bg-light');
                        line.classList.add('bg-koma-primary');
                    }
                } else {
                    circle.classList.remove('bg-koma-primary', 'text-white');
                    circle.classList.add('bg-koma-bg-light', 'text-koma-text-dark');
                    if (line) {
                        line.classList.remove('bg-koma-primary');
                        line.classList.add('bg-koma-bg-light');
                    }
                }
            }
        }

        function updateButtons(step) {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            if (step === 1) {
                prevBtn.classList.add('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                cancelBtn.classList.remove('hidden');
            } else if (step < totalSteps) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
                cancelBtn.classList.add('hidden');
            }
        }

        function changeStep(direction) {
            const newStep = currentStep + direction;
            
            // Validate current step before proceeding
            if (direction > 0 && !validateStep(currentStep)) {
                alert('Mohon lengkapi semua field yang wajib diisi');
                return;
            }
            
            if (newStep >= 1 && newStep <= totalSteps) {
                currentStep = newStep;
                showStep(currentStep);
            }
        }

        function validateStep(step) {
            const form = document.getElementById('registerForm');
            const inputs = document.querySelectorAll(`#step-${step} input[required]`);
            
            for (let input of inputs) {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    return false;
                }
            }
            return true;
        }

        // Clear error styling on input
        document.addEventListener('input', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.required) {
                if (e.target.value.trim()) {
                    e.target.classList.remove('border-red-500');
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            showStep(currentStep);
        });

        const ktpInput = document.getElementById('pic_ktp_number');
        const ktpFeedback = document.getElementById('ktp-feedback');
        const phoneInput = document.getElementById('pic_phone');
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

        // --- Validasi Telepon (Real-time) ---
        function validatePhone() {
            const value = phoneInput.value;
            const phoneRegex = /^(08|\+628)[\d]{8,12}$/;
            
            if (value.length > 0) {
                if (value.length < 10) {
                    phoneFeedback.textContent = `Nomor telepon harus minimal 10 digit. Saat ini: ${value.length}`;
                    phoneFeedback.classList.remove('hidden');
                    phoneInput.classList.remove('border-red-500'); 
                } 
                else if (value.length === 15) {
                    if (phoneRegex.test(value)) {
                        phoneFeedback.classList.add('hidden');
                        phoneInput.classList.remove('border-red-500');
                    } 
                }
                else if (!phoneRegex.test(value)) {
                    phoneFeedback.textContent = "Format telepon tidak valid (contoh: 081xxxxxxxx atau +62...).";
                    phoneFeedback.classList.remove('hidden');
                    phoneInput.classList.add('border-red-500');
                } 
                else {
                    phoneFeedback.classList.add('hidden');
                    phoneInput.classList.remove('border-red-500');
                }
            } else {
                phoneFeedback.classList.add('hidden');
                phoneInput.classList.remove('border-red-500');
            }
        }

        // Event Listeners
        ktpInput?.addEventListener('input', validateKTP);
        phoneInput?.addEventListener('input', validatePhone);

        ktpInput?.addEventListener('keypress', (event) => {
            if (event.key.length === 1 && !/\d/.test(event.key)) {
                event.preventDefault();
            }
        });
        
    </script>
</body>
</html>
