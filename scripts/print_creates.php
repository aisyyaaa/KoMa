<?php
$path = __DIR__ . '/../' . 'database/database.sqlite';
$pdo = new PDO('sqlite:' . $path);
foreach (['categories','products','reviews'] as $t) {
    $r = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='" . $t . "'")->fetch();
    echo $t . ":\n" . $r['sql'] . "\n\n";
}
