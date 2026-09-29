<?php

$dir = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($files as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        $relPath = str_replace(str_replace('\\', '/', $dir) . '/', '', str_replace('\\', '/', $file->getPathname()));
        
        if (preg_match_all('/([^\n]*(?:loader|spinner|preloader)[^\n]*)/i', $content, $m)) {
            echo "=== $relPath (" . count($m[0]) . " matches) ===\n";
            foreach ($m[0] as $line) {
                echo "  " . trim(substr($line, 0, 120)) . "\n";
            }
            echo "\n";
        }
    }
}
