@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Card 1: Total Products --}}
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-10 text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Produk</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['total_products'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            {{-- Card 2: Average Rating --}}
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Rating Rata-Rata</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['average_rating'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            {{-- Card 3: Low Stock --}}
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-1.414 0l-1.414-1.414A1 1 0 009.586 13H7m0 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Stok Rendah</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['low_stock'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            {{-- Card 4: Total Reviews --}}
            <div class="bg-white p-5 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-koma-primary bg-opacity-10 text-koma-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Ulasan</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['total_reviews'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div x-data="dashboard()" x-init="initSummary()" class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <button @click="activeTab = 'ringkasan'" 
                            :class="activeTab === 'ringkasan' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                        Ringkasan
                    </button>
                    <button @click="activeTab = 'produk'; initProductCharts()"
                            :class="activeTab === 'produk' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                        Analisis Produk
                    </button>
                    <button @click="activeTab = 'pelanggan'; initCustomerCharts()"
                            :class="activeTab === 'pelanggan' ? 'border-koma-primary text-koma-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                        Analisis Pelanggan
                    </button>
                </nav>
            </div>

            {{-- TAB PANELS --}}
            <div class="py-6">
                {{-- Ringkasan Panel --}}
                <div x-show="activeTab === 'ringkasan'" x-transition>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Penjualan (7 Hari Terakhir)</h3>
                            <div class="relative h-80"><canvas id="salesTrendChart"></canvas></div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pesanan</h3>
                            <div class="relative h-80"><canvas id="orderStatusChart"></canvas></div>
                        </div>
                    </div>
                </div>
                {{-- Analisis Produk Panel --}}
                <div x-show="activeTab === 'produk'" x-transition>
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
                        <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Stok per Produk</h3>
                            <div class="relative h-96"><canvas id="stockPerProductChart"></canvas></div>
                        </div>
                        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rating per Produk</h3>
                            <div class="relative h-96"><canvas id="productRatingsChart"></canvas></div>
                        </div>
                    </div>
                </div>
                {{-- Analisis Pelanggan Panel --}}
                <div x-show="activeTab === 'pelanggan'" x-transition>
                    <div class="grid grid-cols-1">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sebaran Lokasi Rating</h3>
                            <div class="relative h-96"><canvas id="raterLocationChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function dashboard() {
        return {
            activeTab: 'ringkasan',
            charts: {},

            initSummary() {
                this.initChart('salesTrendChart', 'line', {
                    labels: ['6 hari lalu','5 hari lalu','4 hari lalu','3 hari lalu','2 hari lalu','Kemarin','Hari ini'],
                    datasets: [{ label: 'Penjualan', data: [5,8,6,10,9,12,7], backgroundColor: 'rgba(59,130,246,0.2)', borderColor: 'rgba(59,130,246,1)', fill: true }]
                }, { responsive: true, maintainAspectRatio: false });

                this.initChart('orderStatusChart', 'doughnut', {
                    labels: ['Pending','Dikemas','Dikirim','Selesai'],
                    datasets: [{ data: [8,4,12,20], backgroundColor: ['#F59E0B','#3B82F6','#10B981','#6B7280'] }]
                }, { responsive: true, maintainAspectRatio: false });
            },

            initProductCharts() {
                this.fetchAndCreateChart("{{ route('seller.api.charts.stock_per_product') }}", 'stockPerProductChart', 'bar', 'Stok', 'rgba(16,185,129,0.6)');
                this.fetchAndCreateChart("{{ route('seller.api.charts.rating_per_product') }}", 'productRatingsChart', 'bar', 'Rating', 'rgba(248,113,113,0.6)', { y: { beginAtZero: true, max: 5 } });
            },

            initCustomerCharts() {
                this.fetchAndCreateChart("{{ route('seller.api.charts.rating_by_province') }}", 'raterLocationChart', 'bar', 'Rating', 'rgba(99,102,241,0.6)');
            },

            initChart(canvasId, type, data, options) {
                const ctx = document.getElementById(canvasId);
                if (!ctx) return;

                if (this.charts[canvasId]) {
                    this.charts[canvasId].destroy();
                }

                this.charts[canvasId] = new Chart(ctx.getContext('2d'), { type, data, options });
            },

            fetchAndCreateChart(url, canvasId, type, label, color, scales) {
                axios.get(url)
                    .then(res => {
                        if (res.data) {
                            const data = {
                                labels: res.data.labels,
                                datasets: [{
                                    label: label,
                                    data: res.data.data,
                                    backgroundColor: color
                                }]
                            };
                            this.initChart(canvasId, type, data, {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: scales || {}
                            });
                        }
                    }).catch(err => console.error(`Error fetching data for ${canvasId}:`, err));
            }
        }
    }
</script>
@endpush
