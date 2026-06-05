<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Placeholder' }} - KoMa</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow p-8 max-w-xl w-full text-center">
        <h1 class="text-2xl font-bold mb-2">{{ $title ?? 'Placeholder' }}</h1>
        <p class="text-sm text-gray-600">Halaman ini sementara belum diimplementasikan. Kembali ke <a href="{{ route('seller.dashboard') }}" class="text-koma-primary font-semibold">Dashboard</a>.</p>
    </div>
</body>
</html>