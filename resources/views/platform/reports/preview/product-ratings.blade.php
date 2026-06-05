@extends('layouts.app')
@section('title','Report: Product Ratings')
@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Products by Rating (Preview)</h2>
    <ul>
        @foreach($products as $p)
            <li>{{ $p->name }} — {{ $p->avg_rating }}</li>
        @endforeach
    </ul>
</div>
@endsection
