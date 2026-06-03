<?php

function cleanFile($filePath) {
    $content = file_get_contents($filePath);
    
    // Remove UTF-8 BOM if present
    $bom = pack('H*','EFBBBF');
    $content = preg_replace("/^$bom/", '', $content);
    
    // Remove any leading whitespace or content before <?php
    $pos = strpos($content, '<?php');
    if ($pos !== false && $pos > 0) {
        $content = substr($content, $pos);
    }
    
    file_put_contents($filePath, $content);
}

function processDirectory($dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            cleanFile($file->getPathname());
        }
    }
}

echo "Cleaning files...\n";
processDirectory(__DIR__ . '/app');
processDirectory(__DIR__ . '/routes');
processDirectory(__DIR__ . '/database');
processDirectory(__DIR__ . '/config');
processDirectory(__DIR__ . '/bootstrap');
echo "Done cleaning files.\n";
