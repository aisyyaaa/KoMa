<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Laravel sqlite config: " . config('database.connections.sqlite.database') . PHP_EOL;
$path = config('database.connections.sqlite.database');
if (! file_exists($path)) {
    echo "File does NOT exist: $path" . PHP_EOL;
    exit(0);
}

try {
    $pdo = new PDO('sqlite:' . $path);
    $res = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';");
    echo "Tables:\n";
    foreach ($res as $row) {
        echo "- " . $row['name'] . PHP_EOL;
    }

    echo PHP_EOL . "Migrations rows:\n";
    $res = $pdo->query("SELECT * FROM migrations");
    if ($res) {
        foreach ($res as $r) {
            echo implode(' | ', $r) . PHP_EOL;
        }
    } else {
        echo "(no migrations table or empty)" . PHP_EOL;
    }
} catch (Exception $ex) {
    echo "Error: " . $ex->getMessage() . PHP_EOL;
}
