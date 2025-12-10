<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded shadow">
        <p class="text-sm text-gray-500">Jumlah Penjual</p>
        <p id="total-sellers" class="text-2xl font-bold">-</p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <p class="text-sm text-gray-500">Penjual Aktif / Tidak Aktif</p>
        <p class="text-2xl font-bold"><span id="active-sellers">0</span> / <span id="inactive-sellers">0</span></p>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <p class="text-sm text-gray-500">Pengunjung yang memberi komentar / rating</p>
        <p id="commenters" class="text-2xl font-bold">0</p>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof axios === 'undefined') return;
    axios.get("{{ route('platform.api.stats') }}").then(res => {
        const d = res.data || {};
        document.getElementById('total-sellers').textContent = d.total_sellers ?? '-';
        document.getElementById('active-sellers').textContent = d.active_sellers ?? 0;
        document.getElementById('inactive-sellers').textContent = d.inactive_sellers ?? 0;
        document.getElementById('commenters').textContent = d.commenters ?? 0;
    }).catch(()=>{});
});
</script>
@endpush
