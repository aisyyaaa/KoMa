<?php
$path = __DIR__ . '/../' . 'database/database.sqlite';
if (! file_exists($path)) {
    echo "DB file not found: $path" . PHP_EOL;
    exit(1);
}
try {
    $pdo = new PDO('sqlite:' . $path);
    $tables = ['kategori_produk','produk','komentar_rating'];
    foreach ($tables as $t) {
        echo "Dropping if exists: $t\n";
        $pdo->exec("DROP TABLE IF EXISTS \"$t\"");
    }
    echo "Done.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
