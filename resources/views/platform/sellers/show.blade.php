@extends('layouts.app')

@section('title','Seller Detail')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $seller->store_name }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <p><strong>PIC:</strong> {{ $seller->pic_name }}</p>
            <p><strong>Email:</strong> {{ $seller->pic_email }}</p>
            <p><strong>Phone:</strong> {{ $seller->pic_phone }}</p>
            <p><strong>Address:</strong> {{ $seller->pic_street }}, {{ $seller->pic_village }}, {{ $seller->pic_city }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">Documents</h3>
            <p><a href="#">Lihat KTP</a></p>
            <p><a href="#">Lihat Foto</a></p>
        </div>
    </div>
</div>
@endsection
