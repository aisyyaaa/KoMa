@extends('layouts.app')

@section('title', 'Platform Dashboard')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Platform Dashboard</h1>

    @include('platform.dashboard._stats-cards')

    <div class="mt-6">
        @include('platform.dashboard._charts')
    </div>

    <div class="mt-6">
        @include('platform.dashboard._recent-activities')
    </div>
</div>
@endsection
