@extends('layouts.app')
@section('title','Report: Sellers by Province')
@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Sellers by Province (Preview)</h2>
    <ul>
        @foreach($byProvince as $row)
            <li>{{ $row->pic_province }} — {{ $row->total }}</li>
        @endforeach
    </ul>
</div>
@endsection
