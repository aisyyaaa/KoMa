<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Laporan Daftar Produk Berdasarkan Rating' }}</title>
    {{-- Menggunakan font yang kompatibel dengan DomPDF, seperti DejaVu Sans --}}
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; font-size: 10pt; }
        .header { margin-bottom: 20px; }
        .header .srs { font-size: 10pt; margin-bottom: 5px; }
        .header h1 { font-size: 16pt; margin: 0 0 5px 0; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 10pt; font-style: italic; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        
        .footnote { margin-top: 15px; font-size: 10pt; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        {{-- HEADER BERDASARKAN FORMAT SRS-MartPlace-13 --}}
        <p class="srs">(SRS-MartPlace-13)</p>
        <h1>Laporan Daftar Produk Berdasarkan Rating</h1>
        {{-- Menggunakan variabel dari Controller: $date, $seller --}}
        <p>Tanggal dibuat: {{ $date }} oleh {{ $seller->name ?? $seller->store_name ?? 'NamaAkunPemroses' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Produk</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 15%;">Harga</th>
                <th style="width: 15%;">Stock</th>
                <th style="width: 10%;">Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $i => $product)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    {{-- Di Controller kita sudah memastikan kategori dimuat --}}
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $product->stock }}</td> 
                    {{-- PERBAIKAN KRITIS: Menggunakan properti reviews_avg_rating --}}
                    <td style="text-align: center;">
                        {{ number_format($product->reviews_avg_rating ?? $product->average_rating, 1) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada produk yang tersedia dalam laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footnote">
        ***) diurutkan berdasarkan rating
    </div>
</body>
</html>