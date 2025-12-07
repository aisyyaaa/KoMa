<?php
$path = __DIR__ . '/../' . 'database/database.sqlite';
$pdo = new PDO('sqlite:' . $path);
$q = $pdo->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name IN ('kategori_produk','produk','komentar_rating')");
foreach ($q as $r) {
    echo $r['name'] . " => " . PHP_EOL . $r['sql'] . PHP_EOL . PHP_EOL;
}
