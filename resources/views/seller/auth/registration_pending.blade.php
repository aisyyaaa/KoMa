@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Pendaftaran Sedang Diproses</h1>
    <p class="mb-4">Terima kasih, pendaftaran Anda telah diterima dan sedang diproses oleh tim platform. Silakan tunggu verifikasi dari admin. Kami akan menghubungi Anda melalui email setelah proses selesai.</p>
    <p class="mb-6">Jika ingin, Anda dapat kembali ke beranda atau menutup halaman ini.</p>
    <div class="flex gap-3">
        <a href="{{ route('landingpage.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Kembali ke Beranda</a>
    </div>
</div>
@endsection
