@extends('layouts.app')

@section('title', 'Seller Verification')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Verify Seller: {{ $seller->store_name }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">Info Penjual</h3>
            <p>{{ $seller->pic_name }} - {{ $seller->pic_email }}</p>
            <p>{{ $seller->pic_province }}, {{ $seller->pic_city }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">Dokumen</h3>
            <p><a href="#">Lihat Foto KTP</a></p>
            <p><a href="#">Lihat Foto Diri</a></p>
        </div>
    </div>

    <div class="mt-4">
        @include('platform.verifications._verification-form', ['seller' => $seller])
    </div>
</div>
@endsection
