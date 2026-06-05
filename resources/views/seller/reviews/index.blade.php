@extends('layouts.seller')

@section('title', 'Ulasan Produk')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ulasan Produk Saya</h1>

    @if($reviews->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <p class="text-lg">Belum ada ulasan untuk produk Anda.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $review->visitor_name }}</p>
                        <p class="text-xs text-gray-400">{{ $review->product->name ?? '-' }}</p>
                    </div>
                    <div class="flex items-center gap-1 text-yellow-500">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                        @endfor
                        <span class="text-sm text-gray-600 ml-1">({{ $review->rating }})</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->format('d M Y') }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
