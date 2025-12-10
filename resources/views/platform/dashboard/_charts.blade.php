<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Produk per Kategori</h3>
        <div class="h-64"><canvas id="productsPerCategoryChart"></canvas></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Toko per Provinsi</h3>
        <div class="h-64"><canvas id="sellersPerProvinceChart"></canvas></div>
    </div>
    <div class="bg-white p-4 rounded shadow lg:col-span-2">
        <h3 class="font-semibold mb-2">Statistik Lainnya</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-3">Chart placeholder</div>
            <div class="p-3">Chart placeholder</div>
            <div class="p-3">Chart placeholder</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof axios !== 'undefined' && typeof Chart !== 'undefined') {
            axios.get("{{ route('platform.api.charts.products_per_category') }}").then(res=>{
                const ctx = document.getElementById('productsPerCategoryChart');
                if (ctx) new Chart(ctx.getContext('2d'), { type: 'bar', data: { labels: res.data.labels, datasets: [{ label: 'Produk', data: res.data.data }] }, options: { responsive: true, maintainAspectRatio: false } });
            }).catch(()=>{});

            axios.get("{{ route('platform.api.charts.sellers_per_province') }}").then(res=>{
                const ctx = document.getElementById('sellersPerProvinceChart');
                if (ctx) new Chart(ctx.getContext('2d'), { type: 'bar', data: { labels: res.data.labels, datasets: [{ label: 'Toko', data: res.data.data }] }, options: { responsive: true, maintainAspectRatio: false } });
            }).catch(()=>{});
        }
    });
</script>
@endpush
