<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Verifikasi Penjual | KoMa Market</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-8">

    {{-- Asumsi: Variabel $seller (Model Seller) tersedia di view ini --}}
    @php
        // Ambil email dari model $seller. Jika tidak ada, gunakan Auth::user() atau default.
        $sellerEmail = $seller->email ?? (Auth::guard('seller')->check() ? Auth::guard('seller')->user()->email : 'email Anda');
    @endphp

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-xl">
        {{-- Status Flash Message dari Controller (misal: pendaftaran berhasil) --}}
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->has('login_error'))
             <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm" role="alert">
                {{ $errors->first('login_error') }}
            </div>
        @endif

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-koma-bg-light rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-koma-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-koma-primary mb-2">Akun Sedang Diverifikasi</h2>
            <p class="text-gray-600 text-sm">Terima kasih telah mendaftar sebagai penjual KoMa Market</p>
        </div>

        <div class="bg-koma-bg-light rounded-lg p-6 mb-6 border-l-4 border-koma-accent">
            <h3 class="font-semibold text-koma-text-dark mb-3">Apa yang terjadi selanjutnya?</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                    <span class="inline-block w-5 h-5 rounded-full bg-koma-accent text-white flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">1</span>
                    <span>Tim kami akan memeriksa dokumen Anda dalam 1-2 hari kerja</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-5 h-5 rounded-full bg-koma-accent text-white flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">2</span>
                    {{-- Menggunakan $sellerEmail yang sudah diolah --}}
                    <span>Kami akan mengirimkan email konfirmasi ke <span class="font-semibold text-koma-text-dark">{{ $sellerEmail }}</span></span>
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-5 h-5 rounded-full bg-koma-accent text-white flex items-center justify-center text-xs mr-2 mt-0.5 flex-shrink-0">3</span>
                    <span>Setelah disetujui, Anda bisa mulai berjualan dan akses dashboard lengkap</span>
                </li>
            </ul>
        </div>

        <div class="bg-blue-50 border border-koma-hover-light rounded-lg p-4 mb-6">
            <p class="text-xs text-gray-700">
                <strong>💡 Tips:</strong> Pastikan email Anda aktif dan periksa folder spam jika belum menerima email dari kami.
            </p>
        </div>

        <div class="space-y-3">
            {{-- KOREKSI KRITIS: Mengubah landingpage.index menjadi katalog.index --}}
            <a href="{{ route('katalog.index') }}" 
               class="block w-full py-3 px-4 bg-koma-primary text-white font-semibold rounded-lg 
                      text-center shadow-md hover:bg-koma-danger transition duration-200">
                Kembali ke Halaman Beranda
            </a>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-center text-xs text-gray-500">
                Ada pertanyaan? <a href="#" class="text-koma-primary hover:text-koma-danger font-semibold transition duration-150">
                    Hubungi Support
                </a>
            </p>
        </div>
    </div>

</body>
</html>