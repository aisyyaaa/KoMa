<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-gray-500">
        <p class="text-sm font-medium text-gray-500">Total Penjual</p>
        <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $total_sellers ?? '-' }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
        <p class="text-sm font-medium text-gray-500">Penjual Aktif</p>
        <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $active_sellers ?? 0 }}</p>
    </div>
    {{-- FIX: Tambahkan Penjual Tidak Aktif --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-gray-400"> 
        <p class="text-sm font-medium text-gray-500">Penjual Tidak Aktif</p>
        <p class="text-3xl font-extrabold text-gray-700 mt-1">{{ $inactive_sellers ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-red-500">
        <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
        <p class="text-3xl font-extrabold text-red-600 mt-1">{{ $pending_count ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-koma-primary">
        <p class="text-sm font-medium text-gray-500">Pengunjung yang Berkomentar</p>
        <p class="text-3xl font-extrabold text-koma-primary mt-1">{{ $unique_reviewers ?? 0 }}</p>
    </div>
</div>