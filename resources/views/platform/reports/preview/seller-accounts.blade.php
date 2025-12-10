@extends('layouts.app')
@section('title','Report: Seller Accounts')
@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Seller Accounts Report (Preview)</h2>
    <p>Active: {{ $active ?? 0 }} - Inactive: {{ $inactive ?? 0 }}</p>
</div>
@endsection
