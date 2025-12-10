<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna - KoMa Market</title>
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
                
                <h2 class="text-2xl font-bold mb-4">Selamat Datang!</h2>
                
                <p class="text-sm leading-relaxed mb-6">
                    Bergabunglah dengan komunitas mahasiswa KoMa Market. Belanja mudah, aman, dan terpercaya untuk kebutuhan kampus Anda.
                </p>

                <p class="text-xs opacity-90">
                    Sudah memiliki akun? Masuk sekarang.
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE: Register Form --}}
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 lg:p-8 overflow-y-auto">
            <div class="w-full max-w-sm">
                <div class="lg:hidden text-center mb-6">
                    <h1 class="text-3xl font-bold text-koma-primary mb-1">KoMa</h1>
                    <h2 class="text-xl font-bold text-koma-text-dark">Daftar Pengguna</h2>
                </div>

                <div class="hidden lg:block mb-6">
                    <h2 class="text-xl font-bold text-koma-text-dark">Buat Akun Baru</h2>
                    <p class="text-gray-600 text-xs mt-1">Daftar sebagai pengguna KoMa Market</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded-lg mb-4 text-sm" role="alert">
                        <strong class="font-bold">Pendaftaran Gagal</strong>
                        <ul class="text-xs mt-1 list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('buyer.register.post') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-koma-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name') }}"
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                   @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">
                            Nomor Telepon <span class="text-koma-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            value="{{ old('phone') }}"
                            class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm
                                   focus:outline-none focus:border-koma-primary focus:ring-2 focus:ring-koma-accent
                                   @error('phone') border-red-500 @enderror"
                            placeholder="08xxxxxxxxxx"
                            required>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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

                    <button 
                        type="submit" 
                        class="w-full py-2.5 px-4 bg-koma-primary text-white text-sm font-semibold rounded-lg 
                               shadow-md hover:bg-koma-danger transition duration-200 mb-3">
                        Daftar Sekarang
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
                        Sudah punya akun?
                    </p>
                    <a href="{{ route('login') }}" 
                       class="w-full inline-block py-2.5 px-4 border-2 border-koma-primary text-koma-primary text-sm
                              font-semibold rounded-lg hover:bg-koma-bg-light transition duration-200">
                        Masuk Sekarang
                    </a>
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

</body>
</html>
