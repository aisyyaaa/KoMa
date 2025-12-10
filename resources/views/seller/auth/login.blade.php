<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penjual KoMa Market</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-4">

    <div class="w-full max-w-5xl h-screen max-h-[85vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        
        {{-- LEFT SIDE: Welcome Section (Blue) --}}
        <div class="hidden lg:flex lg:w-1/2 bg-koma-accent flex-col items-center justify-center p-8 overflow-y-auto">
            <div class="text-center text-white max-w-sm">
                <div class="mb-6">
                    <h1 class="text-5xl font-extrabold mb-3">KoMa</h1>
                    <div class="h-1 w-20 bg-white mx-auto"></div>
                </div>
                
                <h2 class="text-2xl font-bold mb-4">Selamat Datang</h2>
                
                <p class="text-sm leading-relaxed mb-6">
                    Bergabunglah dengan ribuan penjual di KoMa Market dan kembangkan bisnis Anda bersama komunitas mahasiswa terpercaya.
                </p>


                <p class="text-xs opacity-90">
                    Sudah jadi bagian KoMa? Masuk sekarang.
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE: Login Form --}}
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 lg:p-8 overflow-y-auto">
            <div class="w-full max-w-sm">
                <div class="lg:hidden text-center mb-6">
                    <h1 class="text-3xl font-bold text-koma-primary mb-1">KoMa</h1>
                    <h2 class="text-xl font-bold text-koma-text-dark">Login Penjual</h2>
                </div>

                <div class="hidden lg:block mb-6">
                    <h2 class="text-xl font-bold text-koma-text-dark">Masuk ke Akun</h2>
                    <p class="text-gray-600 text-xs mt-1">Kelola akun Anda sekarang</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-lg mb-4 text-sm" role="alert">
                        <strong class="font-bold">Login Gagal</strong>
                        <p class="text-xs mt-1">Email atau password tidak sesuai.</p>
                    </div>
                @endif

                <form action="{{ route('seller.auth.login.post') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                            Email <span class="text-koma-danger">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                   @error('email') border-red-500 @enderror"
                            placeholder="name@example.com"
                            required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                            Password <span class="text-koma-danger">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 pr-9 text-sm
                                       focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                       @error('password') border-red-500 @enderror"
                                placeholder="••••••••"
                                required>
                            <button 
                                type="button" 
                                class="absolute right-2.5 top-2.5 text-gray-500 hover:text-koma-primary transition"
                                onclick="togglePasswordVisibility()">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-3.5 h-3.5 border-gray-300 rounded 
                                       text-koma-primary focus:ring-koma-accent"
                                id="remember">
                            <span class="ml-2 text-xs text-gray-600">Ingat saya</span>
                        </label>
                        <a href="#" class="text-xs text-koma-primary hover:text-koma-danger transition duration-150 font-medium">
                            Lupa password?
                        </a>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2.5 px-4 bg-koma-primary text-white text-sm font-semibold rounded-lg 
                               shadow-md hover:bg-koma-danger transition duration-200 mb-3">
                        Masuk
                    </button>
                </form>

                <div class="relative mb-3">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-2 bg-white text-gray-500">Atau</span>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <p class="text-gray-600 text-xs mb-2">
                        Belum punya akun? Pilih pendaftaran:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('buyer.register') }}" 
                           class="inline-block w-full text-center py-2.5 px-4 bg-koma-accent text-white text-sm font-semibold rounded-lg hover:opacity-95 transition duration-200">
                            Daftar sebagai Pengguna
                        </a>
                        <a href="{{ route('seller.auth.register') }}" 
                           class="inline-block w-full text-center py-2.5 px-4 border-2 border-koma-primary text-koma-primary text-sm font-semibold rounded-lg hover:bg-koma-bg-light transition duration-200">
                            Daftar sebagai Penjual
                        </a>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <p class="text-center text-xs text-gray-500">
                        <a href="{{ route('landingpage.index') }}" class="text-koma-primary hover:text-koma-danger font-semibold transition duration-150">
                            Kembali ke Halaman Beranda
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('text-koma-primary');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('text-koma-primary');
            }
        }
    </script>

</body>
</html>
