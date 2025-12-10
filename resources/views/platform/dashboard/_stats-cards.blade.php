<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded shadow">
        <p class="text-sm text-gray-500">Total Sellers</p>
        <p class="text-2xl font-bold">{{ $total_sellers ?? '-' }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <p class="text-sm text-gray-500">Active / Inactive</p>
        <p class="text-2xl font-bold">{{ $active ?? 0 }} / {{ $inactive ?? 0 }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <p class="text-sm text-gray-500">Visitors Commenting</p>
        <p class="text-2xl font-bold">{{ $commenters ?? 0 }}</p>
    </div>
</div>
