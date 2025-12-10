<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Seller Locations</title>
    <style>body{font-family: DejaVu Sans, sans-serif;} table{width:100%;border-collapse:collapse} th,td{border:1px solid #ddd;padding:6px}</style>
</head>
<body>
    <h2>Penjual per Provinsi</h2>
    <table>
        <thead>
            <tr><th>Provinsi</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
            @foreach($byProvince as $row)
            <tr>
                <td>{{ $row->pic_province ?? 'Tidak Diketahui' }}</td>
                <td>{{ $row->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sellers by Province</title>
    <style>body{font-family: DejaVu Sans, sans-serif;}</style>
</head>
<body>
    <h1>Sellers by Province</h1>
    <ul>
        @foreach($byProvince as $row)
            <li>{{ $row->pic_province }} — {{ $row->total }}</li>
        @endforeach
    </ul>
</body>
</html>