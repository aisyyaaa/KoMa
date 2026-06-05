<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Akun Penjual</title>
    <style>
        /* Menggunakan font yang kompatibel dengan DomPDF untuk karakter non-Latin (DejaVu Sans) */
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 0; padding: 0; }
        h2 { font-size: 16px; margin-bottom: 5px; }
        .header-info { margin-bottom: 20px; line-height: 1.5; }
        .metadata { font-size: 10px; }

        /* Tabel Utama */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #e5e7eb; font-weight: bold; }
        .center { text-align: center; }
        .muted { color: #6b7280; }
        .note { margin-top: 15px; font-style: italic; font-size: 9px; }
    </style>
</head>
<body>
    
    {{-- HEADER LAPORAN --}}
    <div class="header-info">
        {{-- JUDUL SESUAI REFERENSI SRS-09 --}}
        <h2>Laporan Daftar Akun Penjual Berdasarkan Status</h2> 
        <div class="metadata">
            Tanggal dibuat: {{ $date ?? now()->format('d-m-Y') }}
            {{-- Menggunakan Nama Akun Pemroses sesuai format di SRS-09 --}}
            @if(isset($pemroses))
                oleh {{ $pemroses->name ?? 'NamaAkunPemroses' }}
            @endif
        </div>
    </div>

    @if(isset($sellers) && $sellers->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="center">No</th>
                {{-- REVISI: Kolom Nama User (Email/ID Login) --}}
                <th style="width: 25%;">Nama User</th> 
                {{-- REVISI: Kolom Nama PIC --}}
                <th style="width: 25%;">Nama PIC</th>
                {{-- REVISI: Kolom Nama Toko --}}
                <th style="width: 30%;">Nama Toko</th>
                {{-- REVISI: Kolom Status --}}
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sellers as $i => $seller)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                {{-- Menggunakan email sebagai Nama User/ID Login --}}
                <td>{{ $seller->email ?? 'XXXXX' }}</td>
                {{-- Kolom Nama PIC (sesuai SRS-09) --}}
                <td>{{ $seller->pic_name ?? 'XXXXXXXXXX XXXX' }}</td>
                {{-- Kolom Nama Toko (sesuai SRS-09) --}}
                <td>{{ $seller->store_name ?? 'XXXXX XXXXX' }}</td>
                <td>
                    {{-- Menggunakan is_active_status (diasumsikan sudah di-map ke 'Aktif'/'Tidak Aktif') --}}
                    {{ $seller->is_active_status ?? 'Tidak Aktif' }} 
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="note">***) Data diurutkan berdasarkan status (Aktif dulu baru Tidak Aktif)</p>
    @else
    <p class="muted">Tidak ada data penjual untuk diekspor.</p>
    @endif

</body>
</html>