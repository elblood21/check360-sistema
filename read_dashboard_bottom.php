<?php

$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);

echo "Total length: " . strlen($content) . "\n";
echo "Last 3000 characters:\n";
echo substr($content, -3000);
