<?php
$dir = __DIR__ . '/../database/migrations';
$files = array_values(array_filter(scandir($dir), function($f){ return substr($f, -4) === '.php'; }));
sort($files);
foreach ($files as $file) {
    echo "--- Running: $file ---\n";
    $cmd = "php artisan migrate --path=database/migrations/" . escapeshellarg($file);
    echo "Cmd: $cmd\n";
    passthru($cmd, $rv);
    echo "Return: $rv\n";
    echo "Tables now:\n";
    passthru("php scripts/list_tables.php");
    echo "\n";
}
