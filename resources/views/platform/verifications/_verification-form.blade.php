<div class="mt-8 flex space-x-4 items-start">

    {{-- 1. FORM APPROVE (Menunjuk ke route APPROVE) --}}
    <form method="POST" action="{{ route('platform.verifications.sellers.approve', $seller) }}">
        @csrf
        <button type="submit" 
                class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-150"
                {{ $seller->status !== 'PENDING' ? 'disabled' : '' }}>
            Setujui
        </button>
    </form>

    {{-- 2. FORM REJECT (Menunjuk ke route REJECT) --}}
    <form method="POST" action="{{ route('platform.verifications.sellers.reject', $seller) }}">
        @csrf
        <div class="flex flex-col space-y-2">
            <div>
                <label for="reason-{{ $seller->id }}" class="block text-xs font-medium text-gray-500 mb-1">Alasan Penolakan (Wajib)</label>
                <textarea name="reason" id="reason-{{ $seller->id }}" 
                          class="w-full border rounded p-2 text-sm resize-none focus:ring-red-500 focus:border-red-500" 
                          rows="2" required 
                          {{ $seller->status !== 'PENDING' ? 'disabled' : '' }}></textarea>
            </div>
            
            <button type="submit" 
                    class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition duration-150"
                    {{ $seller->status !== 'PENDING' ? 'disabled' : '' }}>
                Tolak
            </button>
        </div>
    </form>
</div>