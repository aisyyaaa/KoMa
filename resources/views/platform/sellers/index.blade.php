@extends('layouts.app')

@section('title','Sellers')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">All Sellers</h1>
    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="text-left"><th class="p-2">ID</th><th class="p-2">Store</th><th class="p-2">Status</th><th class="p-2">Actions</th></tr>
        </thead>
        <tbody>
            @foreach($sellers as $s)
            <tr class="border-t"><td class="p-2">{{ $s->id }}</td><td class="p-2">{{ $s->store_name }}</td><td class="p-2">@include('platform.sellers._status-badge', ['status'=>$s->status])</td><td class="p-2">@include('platform.sellers._actions', ['seller'=>$s])</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
