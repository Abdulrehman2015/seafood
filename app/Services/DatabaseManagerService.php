<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;
use Throwable;

class DatabaseManagerService
{
    /**
     * Import a SQL file into the current database connection.
     *
     * @param string $filePath Full path to the .sql or .sql.gz file
     * @return array ['success' => bool, 'statements_count' => int, 'duration' => float, 'message' => string]
     */
    public function importSqlFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'statements_count' => 0,
                'duration' => 0,
                'message' => 'The specified SQL file does not exist on the server.',
            ];
        }

        // Increase limits for database imports
        @set_time_limit(600);
        @ini_set('memory_limit', '512M');

        $startTime = microtime(true);
        $tempDecompressed = null;

        // Check if file is gzip compressed
        if (str_ends_with(strtolower($filePath), '.gz') || $this->isGzipFile($filePath)) {
            $tempDecompressed = storage_path('app/temp/import_' . uniqid() . '.sql');
            $this->ensureDirectoryExists(dirname($tempDecompressed));
            
            $decompressed = $this->decompressGzip($filePath, $tempDecompressed);
            if (!$decompressed) {
                return [
                    'success' => false,
                    'statements_count' => 0,
                    'duration' => 0,
                    'message' => 'Failed to decompress the uploaded .gz archive.',
                ];
            }
            $targetSqlPath = $tempDecompressed;
        } else {
            $targetSqlPath = $filePath;
        }

        $result = $this->executeSqlWithPdo($targetSqlPath);

        // Clean up temporary decompressed file if created
        if ($tempDecompressed && file_exists($tempDecompressed)) {
            @unlink($tempDecompressed);
        }

        $duration = round(microtime(true) - $startTime, 2);
        $result['duration'] = $duration;

        if ($result['success']) {
            $this->clearAppCaches();
        }

        return $result;
    }

    /**
     * Execute SQL file using stream-based PDO parsing.
     */
    protected function executeSqlWithPdo(string $sqlFilePath): array
    {
        $handle = @fopen($sqlFilePath, 'r');
        if (!$handle) {
            return [
                'success' => false,
                'statements_count' => 0,
                'message' => 'Unable to read SQL file.',
            ];
        }

        $pdo = DB::connection()->getPdo();
        
        try {
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
            $pdo->exec("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';");
            $pdo->exec("SET NAMES utf8mb4;");
        } catch (Throwable $e) {
            // Ignore if setting mode/names fails on some drivers
        }

        $currentStatement = '';
        $executedCount = 0;
        $inMultiLineComment = false;
        $inSingleQuote = false;
        $inDoubleQuote = false;
        $inBacktick = false;
        $escaped = false;

        try {
            while (($line = fgets($handle)) !== false) {
                $trimmed = trim($line);

                // Skip single-line comments only when not inside a quoted string
                if (!$inSingleQuote && !$inDoubleQuote && !$inBacktick) {
                    if (str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
                        continue;
                    }
                    if ($trimmed === '') {
                        continue;
                    }
                }

                $lineLen = strlen($line);
                for ($i = 0; $i < $lineLen; $i++) {
                    $char = $line[$i];
                    $nextChar = ($i + 1 < $lineLen) ? $line[$i + 1] : '';

                    // Handle multiline comments /* ... */
                    if ($inMultiLineComment) {
                        if ($char === '*' && $nextChar === '/') {
                            $inMultiLineComment = false;
                            $i++;
                        }
                        continue;
                    }

                    if (!$inSingleQuote && !$inDoubleQuote && !$inBacktick) {
                        if ($char === '/' && $nextChar === '*') {
                            $inMultiLineComment = true;
                            $i++;
                            continue;
                        }
                    }

                    // Handle string quotes
                    if (!$escaped) {
                        if ($char === '\\') {
                            $escaped = true;
                            $currentStatement .= $char;
                            continue;
                        }

                        if ($char === "'" && !$inDoubleQuote && !$inBacktick) {
                            $inSingleQuote = !$inSingleQuote;
                        } elseif ($char === '"' && !$inSingleQuote && !$inBacktick) {
                            $inDoubleQuote = !$inDoubleQuote;
                        } elseif ($char === '`' && !$inSingleQuote && !$inDoubleQuote) {
                            $inBacktick = !$inBacktick;
                        }
                    } else {
                        $escaped = false;
                    }

                    // Statement delimiter ;
                    if ($char === ';' && !$inSingleQuote && !$inDoubleQuote && !$inBacktick && !$inMultiLineComment) {
                        $stmtToRun = trim($currentStatement);
                        if (!empty($stmtToRun)) {
                            $pdo->exec($stmtToRun);
                            $executedCount++;
                        }
                        $currentStatement = '';
                        continue;
                    }

                    $currentStatement .= $char;
                }
            }

            // Execute any remaining statement at end of file
            $remaining = trim($currentStatement);
            if (!empty($remaining)) {
                $pdo->exec($remaining);
                $executedCount++;
            }

            fclose($handle);

            try {
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
            } catch (Throwable $e) {}

            return [
                'success' => true,
                'statements_count' => $executedCount,
                'message' => "Successfully imported and executed {$executedCount} SQL queries.",
            ];

        } catch (Throwable $e) {
            if (is_resource($handle)) {
                fclose($handle);
            }
            try {
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
            } catch (Throwable $ignore) {}

            Log::error('Database import error: ' . $e->getMessage(), [
                'statement' => substr($currentStatement, 0, 500),
                'executed_count' => $executedCount,
            ]);

            return [
                'success' => false,
                'statements_count' => $executedCount,
                'message' => 'SQL Execution Error: ' . $e->getMessage() . ' (at query #' . ($executedCount + 1) . ')',
            ];
        }
    }

    /**
     * Check if a file is gzip compressed.
     */
    protected function isGzipFile(string $filePath): bool
    {
        $handle = @fopen($filePath, 'r');
        if (!$handle) return false;
        $bytes = fread($handle, 2);
        fclose($handle);
        return $bytes === "\x1f\x8b";
    }

    /**
     * Decompress a .gz file to target path.
     */
    protected function decompressGzip(string $src, string $dst): bool
    {
        if (!function_exists('gzopen')) {
            return false;
        }

        $sfp = gzopen($src, 'rb');
        $dfp = fopen($dst, 'wb');

        if (!$sfp || !$dfp) {
            if ($sfp) gzclose($sfp);
            if ($dfp) fclose($dfp);
            return false;
        }

        while (!gzeof($sfp)) {
            fwrite($dfp, gzread($sfp, 4096));
        }

        gzclose($sfp);
        fclose($dfp);

        return file_exists($dst) && filesize($dst) > 0;
    }

    /**
     * Ensure a directory exists.
     */
    protected function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }
    }

    /**
     * Clear application & translation caches after database alteration.
     */
    public function clearAppCaches(): void
    {
        try {
            Cache::flush();
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            if (class_exists(\App\Services\TranslationService::class)) {
                $translationService = app(\App\Services\TranslationService::class);
                $translationService->clearCache();
                $translationService->syncLangFiles();
            }
        } catch (Throwable $e) {
            Log::warning('Error clearing caches after DB import: ' . $e->getMessage());
        }
    }
}
