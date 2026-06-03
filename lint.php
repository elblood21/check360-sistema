<?php
function lintDirectory($dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $output = [];
            $returnVar = 0;
            exec("php -l " . escapeshellarg($file->getPathname()) . " 2>&1", $output, $returnVar);
            if ($returnVar !== 0) {
                echo implode("\n", $output) . "\n";
            }
        }
    }
}

lintDirectory(__DIR__ . '/app');
lintDirectory(__DIR__ . '/routes');
lintDirectory(__DIR__ . '/bootstrap');
lintDirectory(__DIR__ . '/config');
