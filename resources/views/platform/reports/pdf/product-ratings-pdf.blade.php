<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produk berdasarkan Rating</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
        .header { text-align: center; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; font-weight: bold; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Produk berdasarkan Rating</h2>
        <div>Diurutkan berdasarkan rating (menurun)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:28%">Produk</th>
                <th style="width:18%">Toko</th>
                <th style="width:14%">Kategori</th>
                <th style="width:12%">Harga</th>
                <th style="width:12%">Provinsi</th>
                <th style="width:8%">Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->name }}@if(!empty($p->description))<div style="font-size:10px;color:#6b7280">{{ Str::limit($p->description,60) }}</div>@endif</td>
                <td>{{ $p->seller->store_name ?? 'N/A' }}</td>
                <td>{{ $p->category->name ?? 'N/A' }}</td>
                <td class="right">Rp {{ number_format($p->price ?? 0,0,',','.') }}</td>
                <td>{{ $p->seller->pic_province ?? 'N/A' }}</td>
                <td class="right">{{ number_format($p->avg_rating ?? 0, 1) }} ({{ $p->reviews->count() }})</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>