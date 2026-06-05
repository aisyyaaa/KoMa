<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    {{-- Grafik 1: Sebaran Jumlah Produk Berdasarkan Kategori --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-bold mb-4">Sebaran Jumlah Produk Berdasarkan Kategori</h3>
        <div class="h-72 flex items-center justify-center">
            <canvas id="productsPerCategoryChart"></canvas>
            {{-- Fallback jika data kosong atau chart gagal render --}}
            <p id="productsPerCategoryFallback" class="text-gray-500 text-sm hidden">Memuat data...</p>
        </div>
    </div>
    
    {{-- Grafik 2: Sebaran Jumlah Toko Berdasarkan Lokasi Provinsi --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-bold mb-4">Sebaran Jumlah Toko Berdasarkan Lokasi Provinsi</h3>
        <div class="h-72 flex items-center justify-center">
            <canvas id="sellersPerProvinceChart"></canvas>
            {{-- Fallback jika data kosong atau chart gagal render --}}
            <p id="sellersPerProvinceFallback" class="text-gray-500 text-sm hidden">Memuat data...</p>
        </div>
    </div>
</div>

{{-- SCRIPT PENTING: Untuk memanggil API dan merender Chart.js --}}
{{-- Pindahkan skrip Chart.js CDN ke layouts/platform.blade.php untuk dimuat sekali saja --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Fungsi umum untuk mengambil data dan merender chart
    function renderChart(canvasId, url, chartType = 'doughnut') {
        const fallbackElement = document.getElementById(canvasId + 'Fallback');
        if (fallbackElement) fallbackElement.textContent = 'Memuat data...';

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const ctx = document.getElementById(canvasId).getContext('2d');
                
                // Cek jika data kosong
                if (!data.labels || data.labels.length === 0) {
                    if (fallbackElement) {
                        fallbackElement.textContent = 'Data belum tersedia.';
                        fallbackElement.classList.remove('hidden');
                    }
                    return;
                }
                
                if (fallbackElement) fallbackElement.classList.add('hidden');
                
                // --- Logika Warna untuk Pie/Doughnut Chart ---
                let backgroundColors = [];
                if (chartType === 'doughnut' || chartType === 'pie') {
                    const colors = ['#4F46E5', '#EF4444', '#10B981', '#F59E0B', '#6366F1', '#A855F7', '#34D399', '#F97316'];
                    backgroundColors = data.labels.map((_, i) => colors[i % colors.length]);
                }

                new Chart(ctx, {
                    type: chartType,
                    data: {
                        labels: data.labels,
                        datasets: data.datasets.map(dataset => ({
                            label: dataset.label,
                            data: dataset.data,
                            backgroundColor: backgroundColors.length > 0 ? backgroundColors : 'rgba(79, 70, 229, 0.7)',
                            borderColor: backgroundColors.length > 0 ? backgroundColors : '#4F46E5',
                            borderWidth: 1,
                            hoverOffset: 4
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Penting untuk h-72
                        plugins: {
                            legend: {
                                display: chartType !== 'bar',
                                position: 'right',
                            },
                        },
                        scales: chartType === 'bar' ? {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1 }
                            }
                        } : {}
                    }
                });
            })
            .catch(error => {
                console.error(`Error fetching or rendering chart ${canvasId}:`, error);
                if (fallbackElement) {
                    fallbackElement.textContent = `Gagal memuat data analitik: ${error.message}`;
                    fallbackElement.classList.remove('hidden');
                }
            });
    }

    // 1. Render Chart Produk per Kategori (SRS-07)
    renderChart(
        'productsPerCategoryChart',
        '{{ route("platform.api.products_per_category") }}',
        'doughnut' 
    );

    // 2. Render Chart Toko per Provinsi (SRS-07)
    renderChart(
        'sellersPerProvinceChart',
        '{{ route("platform.api.sellers_per_province") }}',
        'bar' 
    );
});
</script>