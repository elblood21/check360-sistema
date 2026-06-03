<?php

$file = 'resources/views/dashboard.blade.php';
$lines = file($file);

foreach ($lines as $idx => $line) {
    if ($idx >= 460 && $idx <= 538) {
        echo ($idx + 1) . ": " . $line;
    }
}
