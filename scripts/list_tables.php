<?php
$path = __DIR__ . '/../' . 'database/database.sqlite';
if (! file_exists($path)) {
    echo "(no DB file)\n";
    exit(0);
}
$pdo = new PDO('sqlite:' . $path);
$q = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
foreach ($q as $r) {
    echo $r['name'] . PHP_EOL;
}
