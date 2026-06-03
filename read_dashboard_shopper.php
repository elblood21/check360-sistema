<?php

$file = 'resources/views/dashboard.blade.php';
$lines = file($file);

foreach ($lines as $idx => $line) {
    if ($idx >= 493 && $idx <= 650) {
        echo ($idx + 1) . ": " . $line;
    }
}
