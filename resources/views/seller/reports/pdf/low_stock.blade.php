<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        /* Gaya CSS disesuaikan untuk format laporan yang lebih formal */
        body { font-family: sans-serif; font-size: 10pt; }
        .header { margin-bottom: 20px; }
        .header h1 { font-size: 16pt; margin: 0 0 5px 0; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 10pt; font-style: italic; }
        .srs { font-size: 12pt; margin-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        
        .footnote { margin-top: 15px; font-size: 10pt; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        {{-- HEADER BERDASARKAN FORMAT SRS-MartPlace-14 --}}
        <p class="srs">(SRS-MartPlace-14)</p>
        <h1>Laporan Daftar Produk Segera Dipesan</h1>
        {{-- Menggunakan variabel dari Controller yang sudah direvisi --}}
        <p>Tanggal dibuat: {{ $date }} oleh {{ $seller->name ?? $seller->store_name ?? 'NamaAkunPemroses' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Produk</th>
                <th style="width: 30%;">Kategori</th>
                <th style="width: 20%;">Harga</th>
                <th style="width: 15%;">Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    {{-- Pastikan relasi category dimuat di Controller --}}
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $product->stock }}</td> 
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada produk yang harus segera dipesan (Stok &lt; 2).</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footnote">
        ***) urutkan berdasarkan kategori dan produk
    </div>
</body>
</html>