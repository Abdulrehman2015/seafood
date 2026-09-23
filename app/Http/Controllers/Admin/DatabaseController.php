<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DatabaseManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;
use Throwable;

class DatabaseController extends Controller
{
    public function __construct(protected DatabaseManagerService $dbManager) {}

    /**
     * Download database snapshot as .sql file.
     */
    public function download(Request $request, $filename = null)
    {
        $backupDir = storage_path('app/backups');
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }

        // If a specific existing backup file from server storage was requested
        if ($filename) {
            $cleanName = basename($filename);
            $targetPath = "{$backupDir}/{$cleanName}";
            if (file_exists($targetPath)) {
                $downloadName = preg_replace('/\.(sql|mysql)$/i', '', $cleanName) . '.sql';
                return response()->download($targetPath, $downloadName, [
                    'Content-Type' => 'application/octet-stream',
                ]);
            }
        }

        $newFilename = ($filename && str_ends_with(strtolower($filename), '.sql'))
            ? basename($filename)
            : ('mst_mysql_backup_' . date('Y-m-d_His') . '.sql');

        $dumpPath = "{$tempDir}/{$newFilename}";

        // Candidate mysqldump locations
        $mysqldumpCandidates = [
            'C:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.4\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];

        $mysqldumpBin = null;
        foreach ($mysqldumpCandidates as $candidate) {
            if (file_exists($candidate)) {
                $mysqldumpBin = "\"{$candidate}\"";
                break;
            }
        }
        if (!$mysqldumpBin) {
            $mysqldumpBin = 'mysqldump';
        }

        $dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database', 'oceanfresh');
        $dbUser = config('database.connections.mysql.username', 'root');
        $dbPass = config('database.connections.mysql.password', '');

        $passArg = !empty($dbPass) ? "-p" . escapeshellarg($dbPass) : "";
        $cmd = "{$mysqldumpBin} --host=" . escapeshellarg($dbHost) . " --port=" . escapeshellarg($dbPort) . " --user=" . escapeshellarg($dbUser) . " {$passArg} --default-character-set=utf8mb4 " . escapeshellarg($dbName) . " > \"{$dumpPath}\"";

        @exec($cmd);

        if (file_exists($dumpPath) && filesize($dumpPath) > 500) {
            return response()->download($dumpPath, $newFilename, [
                'Content-Type' => 'application/octet-stream',
            ])->deleteFileAfterSend(true);
        }

        // Secondary Fallback: Pure PHP PDO SQL Dumper
        try {
            $pdo = DB::connection()->getPdo();
            $tables = [];
            $stmt = $pdo->query('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            if (!empty($tables)) {
                $handle = fopen($dumpPath, 'w');
                fwrite($handle, "-- MST Seafood MySQL Database Dump (.sql)\n");
                fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
                fwrite($handle, "-- Database: `{$dbName}`\n\n");
                fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
                fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n");

                foreach ($tables as $table) {
                    fwrite($handle, "-- Table structure for `{$table}`\n");
                    fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
                    $createRow = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_NUM);
                    if ($createRow && isset($createRow[1])) {
                        fwrite($handle, $createRow[1] . ";\n\n");
                    }

                    fwrite($handle, "-- Data for `{$table}`\n");
                    $rowsStmt = $pdo->query("SELECT * FROM `{$table}`");
                    while ($row = $rowsStmt->fetch(PDO::FETCH_ASSOC)) {
                        $keys = array_map(fn($k) => "`{$k}`", array_keys($row));
                        $vals = array_map(function ($v) use ($pdo) {
                            return is_null($v) ? "NULL" : $pdo->quote($v);
                        }, array_values($row));
                        fwrite($handle, "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $vals) . ");\n");
                    }
                    fwrite($handle, "\n");
                }
                fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
                fclose($handle);

                if (file_exists($dumpPath) && filesize($dumpPath) > 500) {
                    return response()->download($dumpPath, $newFilename, [
                        'Content-Type' => 'application/octet-stream',
                    ])->deleteFileAfterSend(true);
                }
            }
        } catch (Throwable $e) {
            Log::warning('PDO MySQL dump fallback failed: ' . $e->getMessage());
        }

        return back()->with('error', 'Unable to generate MySQL dump. Ensure MySQL service is running.');
    }

    /**
     * Import an uploaded .sql or .sql.gz file into the database.
     */
    public function import(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|max:102400', // 100MB max
        ]);

        $file = $request->file('sql_file');
        $origName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['sql', 'gz', 'txt'])) {
            return redirect()->route('admin.settings.index', ['tab' => 'database'])
                ->with('error', 'Invalid file format. Please upload a .sql or .sql.gz file.');
        }

        $tempPath = $file->getRealPath();

        $result = $this->dbManager->importSqlFile($tempPath);

        if ($result['success']) {
            $msg = "Database successfully imported from '{$origName}'! Executed {$result['statements_count']} SQL statements in {$result['duration']}s.";
            return redirect()->route('admin.settings.index', ['tab' => 'database'])->with('success', $msg);
        } else {
            return redirect()->route('admin.settings.index', ['tab' => 'database'])->with('error', $result['message']);
        }
    }

    /**
     * Restore database from an existing server archive.
     */
    public function restore(Request $request, $filename)
    {
        $backupDir = storage_path('app/backups');
        $cleanName = basename($filename);
        $targetPath = "{$backupDir}/{$cleanName}";

        if (!file_exists($targetPath) || !preg_match('/\.(sql|mysql|gz)$/i', $cleanName)) {
            return redirect()->route('admin.settings.index', ['tab' => 'database'])
                ->with('error', 'The specified backup archive was not found on the server.');
        }

        $result = $this->dbManager->importSqlFile($targetPath);

        if ($result['success']) {
            $msg = "Database successfully restored from archive '{$cleanName}'! Executed {$result['statements_count']} SQL statements in {$result['duration']}s.";
            return redirect()->route('admin.settings.index', ['tab' => 'database'])->with('success', $msg);
        } else {
            return redirect()->route('admin.settings.index', ['tab' => 'database'])->with('error', $result['message']);
        }
    }

    /**
     * Delete stored database backup.
     */
    public function destroy(Request $request, $filename)
    {
        $backupDir = storage_path('app/backups');
        $cleanName = basename($filename);
        $targetPath = "{$backupDir}/{$cleanName}";

        if (file_exists($targetPath) && preg_match('/\.(sql|mysql|gz)$/i', $cleanName)) {
            @unlink($targetPath);
            $msg = "Database backup '{$cleanName}' deleted successfully.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->route('admin.settings.index', ['tab' => 'database'])->with('success', $msg);
        }

        $errMsg = 'Backup file not found or could not be deleted.';
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $errMsg], 404);
        }
        return redirect()->route('admin.settings.index', ['tab' => 'database'])->with('error', $errMsg);
    }

    /**
     * Run pending database migrations directly from admin panel.
     */
    public function runMigrations(Request $request)
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = trim(Artisan::output());
            $this->dbManager->clearAppCaches();

            $msg = 'Database migrations executed successfully. ' . ($output ? "({$output})" : '');
            return redirect()->route('admin.settings.index', ['tab' => 'database'])->with('success', $msg);
        } catch (Throwable $e) {
            return redirect()->route('admin.settings.index', ['tab' => 'database'])
                ->with('error', 'Migration error: ' . $e->getMessage());
        }
    }

    /**
     * Run database seeders (e.g. TranslationSeeder) directly from admin panel.
     */
    public function runSeeders(Request $request)
    {
        $seederClass = $request->input('seeder_class', 'Database\\Seeders\\TranslationSeeder');
        
        $allowed = [
            'Database\\Seeders\\TranslationSeeder' => 'Translation Seeder',
            'Database\\Seeders\\MultilingualCatalogueSeeder' => 'Multilingual Catalogue Seeder',
        ];

        if (!array_key_exists($seederClass, $allowed)) {
            return redirect()->route('admin.settings.index', ['tab' => 'database'])
                ->with('error', 'Invalid seeder class requested.');
        }

        try {
            Artisan::call('db:seed', ['--class' => $seederClass, '--force' => true]);
            $this->dbManager->clearAppCaches();

            $name = $allowed[$seederClass];
            return redirect()->route('admin.settings.index', ['tab' => 'database'])
                ->with('success', "Seeder '{$name}' executed and cache synchronized successfully.");
        } catch (Throwable $e) {
            return redirect()->route('admin.settings.index', ['tab' => 'database'])
                ->with('error', 'Seeding error: ' . $e->getMessage());
        }
    }
}
