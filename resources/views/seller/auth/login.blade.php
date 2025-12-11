<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Penjual KoMa Market</title>
    @vite('resources/css/app.css') 
</head>
{{-- Mengganti warna background body menjadi lebih netral --}}
<body class="bg-white min-h-screen flex items-center justify-center p-4">

    {{-- CONTAINER UTAMA: Diubah dari max-w-6xl menjadi max-w-4xl agar lebih ringkas --}}
    {{-- Tinggi dikurangi menjadi min-h-[550px] dan border-radius diperhalus menjadi rounded-xl --}}
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-[550px]">
        
        {{-- LEFT SIDE: Welcome Section (Branding) --}}
        {{-- Warna accent diubah menjadi lebih solid, shadow dihapus untuk kesan modern --}}
        <div class="hidden lg:flex lg:w-5/12 bg-koma-accent/95 text-white flex-col items-center justify-center p-8">
            <div class="text-center max-w-xs">
                <div class="mb-10">
                    {{-- Ukuran font KoMa dikurangi dari 6xl menjadi 5xl --}}
                    <h1 class="text-5xl font-extrabold mb-2 tracking-wider">KoMa</h1>
                    <div class="h-1.5 w-16 bg-white mx-auto rounded-full opacity-75"></div>
                </div>
                
                {{-- Ukuran font heading dikurangi dari 2xl menjadi xl --}}
                <h2 class="text-xl font-semibold mb-3">Dashboard Penjual</h2>
                
                {{-- Ukuran font deskripsi dipertahankan, namun leadingnya disederhanakan --}}
                <p class="text-sm opacity-90 mb-6">
                    Kelola toko, produk, dan laporan penjualan Anda di komunitas mahasiswa.
                </p>

                <p class="text-xs font-light mt-4 opacity-80">
                    Pusat Jual Beli Mahasiswa
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE: Login Form --}}
        {{-- Lebar diubah menjadi 7/12 dan padding diperkecil --}}
        <div class="w-full lg:w-7/12 flex flex-col items-center justify-center p-6 sm:p-10 lg:p-12 overflow-y-auto">
            {{-- max-w-sm memastikan form di kanan tidak terlalu lebar --}}
            <div class="w-full max-w-sm">
                
                {{-- Judul dan Subjudul --}}
                {{-- Ukuran Judul dikurangi dari 4xl menjadi 3xl --}}
                <h2 class="text-3xl font-extrabold text-koma-text-dark mb-1 text-center lg:text-left">
                    Masuk Akun
                </h2>
                {{-- Ukuran subjudul dipertahankan, margin bawah dikurangi --}}
                <p class="text-gray-600 text-sm mb-6 text-center lg:text-left">
                    Akses Dashboard Penjual Anda.
                </p>

                {{-- Alert Sukses --}}
                @if (session('status'))
                    <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-4 text-xs" role="alert">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif
                
                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-xs" role="alert">
                        <strong class="font-bold block mb-1">Login Gagal</strong>
                        @if ($errors->has('login_error'))
                            <p>{{ $errors->first('login_error') }}</p>
                        @else
                            <p>Email atau password tidak valid. Silakan coba lagi.</p>
                        @endif
                    </div>
                @endif

                {{-- Form Login --}}
                <form action="{{ route('seller.auth.login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        {{-- Padding input diubah dari p-3 menjadi p-2.5, rounded menjadi rounded-lg --}}
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            class="w-full border rounded-lg shadow-sm p-2.5 text-sm transition duration-150 focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-accent/50 @error('email') border-red-500 @else border-gray-300 @enderror"
                            placeholder="name@example.com"
                            required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            {{-- Padding input diubah dari p-3 menjadi p-2.5, rounded menjadi rounded-lg --}}
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="w-full border rounded-lg shadow-sm p-2.5 pr-10 text-sm transition duration-150 focus:outline-none focus:border-koma-primary focus:ring-1 focus:ring-koma-accent/50 @error('password') border-red-500 @else border-gray-300 @enderror"
                                placeholder="••••••••"
                                required>
                            {{-- Tombol Toggle Password --}}
                            <button 
                                type="button" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-koma-primary transition"
                                onclick="togglePasswordVisibility()">
                                {{-- Ukuran SVG Icon dikurangi dari w-5 h-5 menjadi w-4 h-4 --}}
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Ingat Saya & Lupa Password --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 border-gray-300 rounded text-koma-primary focus:ring-koma-accent"
                                id="remember">
                            {{-- Ukuran text "Ingat saya" diubah menjadi text-xs --}}
                            <span class="ml-2 text-xs text-gray-600">Ingat saya</span>
                        </label>
                        {{-- Ukuran text "Lupa password?" diubah menjadi text-xs --}}
                        <a href="#" class="text-xs text-koma-primary hover:text-koma-danger transition duration-150 font-medium">
                            Lupa password?
                        </a>
                    </div>

                    {{-- Tombol Masuk --}}
                    {{-- Padding dan margin diubah agar lebih ramping --}}
                    <button 
                        type="submit" 
                        class="w-full py-2.5 px-4 bg-koma-primary text-white text-sm font-semibold rounded-lg mt-5
                                shadow-lg shadow-koma-primary/30 hover:bg-koma-danger transition duration-200">
                        Masuk
                    </button>
                </form>

                {{-- Tautan Registrasi & Kembali ke Beranda --}}
                <div class="text-center mt-6">
                    <p class="text-gray-600 text-sm">
                        Belum punya akun?
                        <a href="{{ route('seller.register') }}" class="text-koma-primary hover:text-koma-danger font-semibold transition duration-150">
                            Daftar Sebagai Penjual
                        </a>
                    </p>
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <a href="{{ route('katalog.index') }}" class="text-sm text-koma-accent hover:text-koma-primary font-semibold mt-3 block transition duration-150">
                            ← Kembali ke Halaman Beranda
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Toggle Password --}}
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Mengganti ikon ke "mata terbuka" (Open Eye)
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.549 0 3.033.425 4.375 1.175M16 12a4 4 0 10-8 0 4 4 0 008 0z"></path>`; 
                eyeIcon.classList.add('text-koma-primary');
            } else {
                passwordInput.type = 'password';
                // Mengganti ikon kembali ke "mata tertutup" (Closed Eye)
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
                eyeIcon.classList.remove('text-koma-primary');
            }
        }
    </script>

</body>
</html>