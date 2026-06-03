<?php

$file = 'resources/views/dashboard.blade.php';
$lines = file($file);

foreach ($lines as $idx => $line) {
    // Find the @else or end of shopper section
    if ($idx >= 1000 && $idx <= 1195) {
        echo ($idx + 1) . ": " . $line;
    }
}
