<?php

$dir = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$nakedCount = 0;

foreach ($files as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        $relPath = str_replace(str_replace('\\', '/', $dir) . '/', '', str_replace('\\', '/', $file->getPathname()));
        
        // Skip layout files where <style> in <head> is intentional
        if (in_array($relPath, ['layouts/app.blade.php', 'layouts/admin.blade.php', 'layouts/guest.blade.php', 'layouts/navigation.blade.php'])) {
            continue;
        }

        if (preg_match_all('/<style\b[^>]*>/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                $offset = $match[1];
                $before = substr($content, 0, $offset);
                $lastPush = strrpos($before, "@push('styles')");
                $lastEndPush = strrpos($before, "@endpush");
                
                $isInsidePush = ($lastPush !== false && ($lastEndPush === false || $lastPush > $lastEndPush));
                if (!$isInsidePush) {
                    echo "Naked style tag in: $relPath (offset $offset)\n";
                    $nakedCount++;
                }
            }
        }
    }
}

echo "Scan complete. Total naked style tags: $nakedCount\n";
