<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Akun Penjual</title>
    <style>
        body{font-family: DejaVu Sans, sans-serif;font-size:12px}
        table{width:100%;border-collapse:collapse;margin-top:10px}
        th,td{border:1px solid #ddd;padding:6px;text-align:left}
        th{background:#f3f4f6}
        .muted{color:#6b7280}
    </style>
</head>
<body>
    <h2>Laporan Akun Penjual</h2>
    <p class="muted">Ringkasan: Aktif: {{ $active ?? 0 }} — Tidak aktif: {{ $inactive ?? 0 }}</p>

    @if(isset($sellers) && $sellers->count() > 0)
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Toko</th>
                <th>Nama PIC</th>
                <th>Email PIC</th>
                <th>Telepon PIC</th>
                <th>Status</th>
                <th>Terakhir Aktif</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sellers as $i => $seller)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $seller->store_name }}</td>
                <td>{{ $seller->pic_name ?? '-' }}</td>
                <td>{{ $seller->pic_email ?? '-' }}</td>
                <td>{{ $seller->pic_phone ?? '-' }}</td>
                <td>{{ strtoupper($seller->status) === 'ACTIVE' ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td>{{ optional($seller->last_active)->format('Y-m-d H:i') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="muted">Tidak ada data penjual untuk diekspor.</p>
    @endif

</body>
</html>