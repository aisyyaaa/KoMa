<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sebaran Jumlah Produk Berdasarkan Kategori</h3>
        <div class="h-72"><canvas id="productsPerCategoryChart"></canvas></div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sebaran Jumlah Toko Berdasarkan Lokasi Provinsi</h3>
        <div class="h-72"><canvas id="sellersPerProvinceChart"></canvas></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof axios !== 'undefined' && typeof Chart !== 'undefined') {
            axios.get("{{ route('platform.api.products_per_category') }}").then(res=>{
                const ctx = document.getElementById('productsPerCategoryChart');
                if (ctx) new Chart(ctx.getContext('2d'), { type: 'bar', data: { labels: res.data.labels, datasets: [{ label: 'Produk', data: res.data.data }] }, options: { responsive: true, maintainAspectRatio: false } });
            }).catch(()=>{});

            axios.get("{{ route('platform.api.sellers_per_province') }}").then(res=>{
                const ctx = document.getElementById('sellersPerProvinceChart');
                if (ctx) new Chart(ctx.getContext('2d'), { type: 'bar', data: { labels: res.data.labels, datasets: [{ label: 'Toko', data: res.data.data }] }, options: { responsive: true, maintainAspectRatio: false } });
            }).catch(()=>{});
        }
    });
</script>
@endpush
