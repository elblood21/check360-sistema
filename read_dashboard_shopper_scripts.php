<?php

$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);

// Find occurrences of "filtro-cocina" in the scripts
$pos = 0;
while (($pos = stripos($content, 'filtro-cocina', $pos)) !== false) {
    echo "Found at offset $pos: " . substr($content, max(0, $pos - 100), 200) . "\n\n";
    $pos += 15;
}
