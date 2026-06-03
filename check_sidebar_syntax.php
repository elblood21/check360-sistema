<?php

$file = 'resources/views/layouts/sidebar.blade.php';
$content = file_get_contents($file);

echo "Length: " . strlen($content) . "\n";
echo "First 200 bytes (hex): " . bin2hex(substr($content, 0, 200)) . "\n";
echo "First 200 bytes (text):\n" . substr($content, 0, 200) . "\n";
