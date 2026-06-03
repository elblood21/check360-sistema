<?php

$file = 'resources/views/dashboard.blade.php';
$lines = file($file);

foreach ($lines as $idx => $line) {
    if ($idx >= 1050 && $idx <= 1350) {
        echo ($idx + 1) . ": " . $line;
    }
}
