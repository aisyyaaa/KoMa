<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Lokasi Penjual</title>
    <style>
        /* (Style tetap sama) */
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 0; padding: 0; }
        h2 { font-size: 16px; margin-bottom: 5px; }
        .header-info { margin-bottom: 20px; line-height: 1.5; }
        .metadata { font-size: 10px; }

        /* Tabel Utama */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #e5e7eb; font-weight: bold; }
        .center { text-align: center; }
        .muted { color: #6b7280; }
        .note { margin-top: 15px; font-style: italic; font-size: 9px; }
    </style>
</head>
<body>
    
    {{-- HEADER LAPORAN (SRS-MartPlace-10) --}}
    <div class="header-info">
        <h2>Laporan Daftar Toko Berdasarkan Lokasi Provinsi (SRS-MartPlace-10)</h2>
        <div class="metadata">
            Tanggal dibuat: {{ $date ?? now()->format('d-m-Y') }}
            @if(isset($pemroses))
                oleh {{ $pemroses->name ?? 'NamaAkunPemroses' }}
            @endif
        </div>
    </div>

    @php
        // Menggunakan $groupedSellers yang dikirim dari Controller
        $groupedSellers = $groupedSellers ?? collect([]); 
        
        // REVISI KRITIS: Hanya meratakan koleksi. JANGAN SORTIR di VIEW, biarkan urutan dari Controller (By Count DESC).
        $flatSellers = $groupedSellers->flatten();
        $counter = 0;
    @endphp

    @if($flatSellers->isEmpty())
        <p class="muted">Tidak ada data penjual untuk diekspor.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;" class="center">No</th>
                    <th style="width: 35%;">Nama Toko</th>
                    <th style="width: 35%;">Nama PIC</th>
                    <th style="width: 25%;">Provinsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flatSellers as $seller)
                    @php $counter++; @endphp
                    <tr>
                        <td class="center">{{ $counter }}</td> 
                        <td>{{ $seller->store_name ?? 'XXXXX' }}</td>
                        <td>{{ $seller->pic_name ?? 'XXXXXXX' }}</td>
                        <td>{{ $seller->province ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p class="note">***) Data diurutkan berdasarkan provinsi (Jumlah Toko Terbanyak)</p>

</body>
</html>