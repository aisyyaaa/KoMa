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