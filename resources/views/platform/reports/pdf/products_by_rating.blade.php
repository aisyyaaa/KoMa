<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Daftar Produk Berdasarkan Rating (Platform)</title>
    {{-- Gaya CSS in-line agar DomPDF dapat merender dengan benar --}}
    <style>
        /* Menggunakan font yang kompatibel dengan DomPDF untuk karakter non-Latin (DejaVu Sans) */
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 0; font-size: 9pt; }
        .header { margin-bottom: 15px; }
        .header .srs { font-size: 10pt; margin-bottom: 5px; font-weight: bold; }
        .header h1 { font-size: 14pt; margin: 0 0 5px 0; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 10pt; font-style: italic; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; word-wrap: break-word; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .right { text-align: right; }
        .center { text-align: center; }
        
        .footnote { margin-top: 15px; font-size: 9pt; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        {{-- HEADER BERDASARKAN FORMAT SRS-MartPlace-11 --}}
        <p class="srs">(SRS-MartPlace-11)</p>
        <h1>Laporan Daftar Produk Berdasarkan Rating</h1>
        {{-- Menggunakan variabel dari Controller: $date, $pemroses --}}
        <p>Tanggal dibuat: {{ $date ?? 'xx-xx-xxxx' }} oleh {{ $pemroses->name ?? 'NamaAkunPemroses' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                {{-- Nama Kolom disinkronkan dengan mockup SRS-11 --}}
                <th style="width: 25%;">Produk</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 12%;">Harga</th>
                <th style="width: 12%;">Rating</th>
                <th style="width: 20%;">Nama Toko</th>
                <th style="width: 11%;">Propinsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $i => $p)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $p->name ?? 'XXXXXX' }}</td>
                    {{-- Kategori --}}
                    <td>{{ $p->category->name ?? 'XXXXXX' }}</td>
                    {{-- Harga --}}
                    <td class="right">Rp {{ number_format($p->price ?? 0, 0, ',', '.') }}</td>
                    {{-- Rating --}}
                    <td class="center">
                        {{ number_format($p->reviews_avg_rating ?? 0, 1) }}
                    </td>
                    {{-- Nama Toko --}}
                    <td>{{ $p->seller->store_name ?? 'XXXXXX XXXXXX' }}</td> 
                    {{-- Provinsi Toko (Karena data provinsi pemberi rating tidak diambil di Controller) --}}
                    <td>{{ $p->seller->province ?? 'Propinsi' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada produk yang tersedia dalam laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footnote">
        ***) propinsi diisikan propinsi pemberi rating
    </div>
</body>
</html>