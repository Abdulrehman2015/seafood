<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=======================================================\n";
echo "GLOBAL SEARCH & AUDIT FOR FORBIDDEN / OUTDATED STRINGS\n";
echo "=======================================================\n\n";

$searchPaths = [
    'resources/views',
    'app',
    'routes',
    'lang',
    'database/seeders',
];

$patterns = [
    'fresh-loligo' => 'misleading fresh loligo slug',
    'Minimum Order' => 'hard minimum order wording',
    'Minimum Purchase' => 'hard minimum purchase wording',
    'RM100 minimum' => 'RM100 minimum wording',
    'RM350 minimum' => 'RM350 minimum wording',
    'harga berperingkat' => 'BM tiered pricing phrase',
    'Perolehan Tersuai' => 'Old custom sourcing term in BM',
    'Sumber Tersuai' => 'Old custom sourcing term in BM',
    'Sashimi Grade' => 'Sashimi grade claims',
    '扇贝' => 'Scallop in Chinese (check if category shellfish uses it)',
    'Counter 2' => 'Counter 2 references',
    'Kaunter 2' => 'Kaunter 2 references',
    '2号柜台' => '2号柜台 references',
    'global supply' => 'improper global supply claim',
    'international supply' => 'improper international supply claim',
    'worldwide delivery' => 'improper worldwide delivery claim',
    'Malaysia, Singapore and selected markets' => 'old market description',
    'javascript:void(0)' => 'legacy javascript href',
];

$baseDir = base_path();

foreach ($patterns as $query => $desc) {
    echo "--- Search: '{$query}' ({$desc}) ---\n";
    $matches = [];
    foreach ($searchPaths as $sp) {
        $dir = $baseDir . '/' . $sp;
        if (!is_dir($dir)) continue;

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isDir()) continue;
            $ext = $file->getExtension();
            if (!in_array($ext, ['php', 'json', 'blade.php', 'js', 'html', 'xml'])) continue;
            
            $content = file_get_contents($file->getPathname());
            if (stripos($content, $query) !== false) {
                // Count lines
                $lines = explode("\n", $content);
                foreach ($lines as $lnIdx => $line) {
                    if (stripos($line, $query) !== false) {
                        $relPath = str_replace($baseDir . '/', '', $file->getPathname());
                        $relPath = str_replace($baseDir . '\\', '', $relPath);
                        $matches[] = sprintf("  %s:%d -> %s", $relPath, $lnIdx + 1, trim($line));
                        if (count($matches) > 10) {
                            $matches[] = "  ... (more matches truncated)";
                            break 2;
                        }
                    }
                }
            }
        }
    }

    if (empty($matches)) {
        echo "  [CLEAN] No occurrences found.\n\n";
    } else {
        foreach ($matches as $m) {
            echo $m . "\n";
        }
        echo "\n";
    }
}
