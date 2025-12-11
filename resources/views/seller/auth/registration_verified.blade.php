@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Pendaftaran Terverifikasi</h1>
    <p class="mb-4">Selamat! Pendaftaran Anda telah berhasil diverifikasi oleh admin platform.</p>
    <p class="mb-4">Silakan masuk untuk melanjutkan menggunakan akun penjual Anda.</p>
    <a href="{{ url('/seller/login') }}" class="px-4 py-2 bg-green-600 text-white rounded">Masuk sebagai Penjual</a>
</div>
@endsection
