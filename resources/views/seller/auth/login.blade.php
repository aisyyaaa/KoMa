<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Penjual KoMa Market</title>
    {{-- Memastikan styling Tailwind di-load --}}
    @vite('resources/css/app.css') 
    {{-- Kita asumsikan Anda memiliki warna kustom di tailwind.config.js, 
         misalnya: bg-koma-accent, text-koma-primary, dll. --}}
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    {{-- Container utama: Diperbaiki agar card responsif dan tetap di tengah --}}
    <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-[550px] max-h-[90vh]">
        
        {{-- LEFT SIDE: Welcome Section (Blue) --}}
        <div class="hidden lg:flex lg:w-5/12 bg-koma-accent flex-col items-center justify-center p-8">
            <div class="text-center text-white max-w-sm">
                <div class="mb-8">
                    <h1 class="text-5xl font-extrabold mb-3 tracking-wide">KoMa</h1>
                    <div class="h-1.5 w-20 bg-white mx-auto rounded-full"></div>
                </div>
                
                <h2 class="text-2xl font-semibold mb-3">Selamat Datang</h2>
                
                <p class="text-sm opacity-90 leading-relaxed mb-6">
                    Bergabunglah dengan ribuan penjual di KoMa Market dan kembangkan bisnis Anda bersama komunitas mahasiswa terpercaya.
                </p>

                <p class="text-xs font-light mt-4">
                    Pusat Jual Beli Mahasiswa
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE: Login Form --}}
        <div class="w-full lg:w-7/12 flex flex-col items-center justify-center p-6 sm:p-10 lg:p-12 overflow-y-auto">
            <div class="w-full max-w-md">
                
                {{-- Judul dan Subjudul --}}
                <h2 class="text-3xl font-bold text-koma-text-dark mb-2 text-center lg:text-left">
                    Masuk ke Akun Penjual
                </h2>
                <p class="text-gray-600 text-sm mb-8 text-center lg:text-left">
                    Masukkan email dan kata sandi Anda untuk melanjutkan.
                </p>

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm" role="alert">
                        <strong class="font-bold block mb-1">Login Gagal</strong>
                        <p class="text-xs">Email atau password tidak sesuai. Silakan coba lagi.</p>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            class="w-full border rounded-xl shadow-sm p-3 text-sm transition duration-150
                                focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent/50 
                                {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="name@example.com"
                            required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="w-full border rounded-xl shadow-sm p-3 pr-10 text-sm transition duration-150
                                        focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent/50 
                                        {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                                placeholder="••••••••"
                                required>
                            {{-- Tombol Toggle Password --}}
                            <button 
                                type="button" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-koma-primary transition"
                                onclick="togglePasswordVisibility()">
                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ingat Saya & Lupa Password --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 border-gray-300 rounded text-koma-primary focus:ring-koma-accent"
                                id="remember">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-koma-primary hover:text-koma-danger transition duration-150 font-medium">
                            Lupa password?
                        </a>
                    </div>

                    {{-- Tombol Masuk --}}
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 bg-koma-primary text-white text-base font-semibold rounded-xl mt-6
                                shadow-lg shadow-koma-primary/50 hover:bg-koma-danger transition duration-200">
                        Masuk
                    </button>
                </form>

                {{-- Tautan Lain --}}
                <div class="text-center mt-8">
                    <p class="text-gray-600 text-sm">
                        Belum punya akun? 
                        <a href="{{ route('register.choice') }}" class="text-koma-primary hover:text-koma-danger font-semibold transition duration-150">
                            Daftar Sekarang
                        </a>
                    </p>
                    <div class="pt-4 mt-6 border-t border-gray-200">
                        <a href="{{ route('katalog.index') }}" class="text-sm text-koma-accent hover:text-koma-primary font-semibold transition duration-150">
                            ← Kembali ke Halaman Beranda
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Toggle Password tetap sama --}}
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Mengubah ikon menjadi mata terbuka jika perlu, dan berikan warna aktif
                eyeIcon.setAttribute('d', 'M10 12a2 2 0 100-4 2 2 0 000 4z'); 
                eyeIcon.classList.add('text-koma-primary');
            } else {
                passwordInput.type = 'password';
                // Mengubah ikon menjadi mata tertutup dan menghilangkan warna aktif
                eyeIcon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
                eyeIcon.classList.remove('text-koma-primary');
            }
        }
    </script>

</body>
</html>