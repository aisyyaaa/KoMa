<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products by Rating</title>
    <style>body{font-family: DejaVu Sans, sans-serif;}</style>
</head>
<body>
    <h1>Products by Rating</h1>
    <ul>
        @foreach($products as $p)
            <li>{{ $p->name }} — {{ $p->avg_rating }}</li>
        @endforeach
    </ul>
</body>
</html>