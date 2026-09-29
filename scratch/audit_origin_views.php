<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/../resources/views');
$iterator = new RecursiveIteratorIterator($dir);
$matched = [];

foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $path = $file->getPathname();
        $rel = str_replace('\\', '/', substr($path, strpos($path, 'resources')));
        
        // Skip detail pages as instructed (decision deferred)
        if (str_contains($rel, 'show.blade.php')) {
            continue;
        }
        
        $content = file_get_contents($path);
        
        // Check for origin references
        if (preg_match_all('/(origin|flag|badge-origin|qvOriginTag|\$product->origin|\$p->origin|\$item->origin)/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $m) {
                $offset = $m[1];
                $start = max(0, $offset - 50);
                $snippet = substr($content, $start, 120);
                $matched[$rel][] = trim(preg_replace('/\s+/', ' ', $snippet));
            }
        }
    }
}

foreach ($matched as $file => $snippets) {
    echo "FILE: $file (" . count($snippets) . " matches)\n";
    foreach (array_slice($snippets, 0, 10) as $s) {
        echo "  - $s\n";
    }
    echo "\n";
}
