<?php

$views = glob(__DIR__ . '/../resources/views/*.blade.php');
$views = array_merge($views, glob(__DIR__ . '/../resources/views/**/*.blade.php'));
$views = array_merge($views, glob(__DIR__ . '/../resources/views/**/**/*.blade.php'));

foreach (array_unique($views) as $file) {
    $content = file_get_contents($file);
    $rel = str_replace('\\', '/', substr($file, strpos($file, 'resources')));
    
    // Check if there is <style> outside @push('styles')
    if (preg_match_all('/<style[\s>]/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[0] as $m) {
            $offset = $m[1];
            $before = substr($content, 0, $offset);
            $lastPush = strrpos($before, "@push('styles')");
            $lastEndPush = strrpos($before, '@endpush');
            
            $isInsidePush = ($lastPush !== false && ($lastEndPush === false || $lastPush > $lastEndPush));
            if (!$isInsidePush && !str_contains($rel, 'layouts/app.blade.php') && !str_contains($rel, 'layouts/admin.blade.php') && !str_contains($rel, 'emails/')) {
                echo "File $rel has <style> NOT in @push('styles') at char $offset\n";
            }
        }
    }
}
