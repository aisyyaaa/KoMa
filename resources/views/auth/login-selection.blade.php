<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Masuk / Daftar - KoMa</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white text-koma-text-dark">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full p-6 border rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-4">Masuk atau Daftar</h1>

            @if(session('status'))
                <div class="mb-3 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <p class="mb-4">Pilih jenis akun untuk melanjutkan:</p>

            <div class="space-y-3">
                <a href="{{ route('buyer.register') }}" class="block text-center px-4 py-2 bg-koma-accent text-white rounded">Daftar sebagai Pengguna</a>
                <a href="{{ route('seller.auth.register') }}" class="block text-center px-4 py-2 border border-koma-accent text-koma-accent rounded">Daftar / Masuk sebagai Penjual</a>
            </div>

            <p class="text-xs text-gray-500 mt-4">Atau, jika sudah memiliki akun penjual, gunakan halaman penjual.</p>
        </div>
    </div>
</body>
</html>
