<?php

namespace App\Http\Controllers;

use App\Models\DatabaseConnection;
use App\Models\Backup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    // public function run(DatabaseConnection $database)
    // {
    //     $timestamp = now()->format('Ymd_His');
    //     $filename = "{$database->dbname}_{$timestamp}.sql";
    //     $backupPath = storage_path("app/backups/{$filename}");

    //     if ($database->type === 'mysql') {
    //         $passwordOption = $database->password ? "-p'{$database->password}'" : '';
    //        // $cmd = "mysqldump -h {$database->host} -u {$database->username} {$passwordOption} {$database->dbname} > \"{$backupPath}\"";
    //        $cmd = "mysqldump -h {$database->host} -u {$database->username} {$passwordOption} {$database->dbname} > \"{$backupPath}\" 2>&1";

    //     } else {
    //         $passwordOption = $database->password ? "PGPASSWORD='{$database->password}' " : '';
    //         $cmd = "{$passwordOption}pg_dump -h {$database->host} -U {$database->username} -d {$database->dbname} > \"{$backupPath}\"";
    //     }

    //     $result = null;
    //     $output = null;

    //     // Temporairement, ajoute ceci juste avant exec()
    //     $errorLogPath = storage_path("logs/backup_error_" . now()->format('Ymd_His') . ".log");

    //     $cmd = "mysqldump -h {$database->host} -u {$database->username} {$passwordOption} {$database->dbname} > \"{$backupPath}\" 2> \"{$errorLogPath}\"";

    //             exec($cmd, $output, $result);

            

    //     if ($result !== 0) {
    //         Log::error("Erreur backup: " . implode("\n", $output));
    //     }


    //     Backup::create([
    //         'db_connection_id' => $database->id,
    //         'file_path' => "backups/{$filename}",
    //         'status' => $result === 0 ? 'success' : 'fail',
    //     ]);

    //     return back()->with($result === 0 ? 'success' : 'error', $result === 0
    //         ? "✅ Sauvegarde réussie pour {$database->dbname}"
    //         : "❌ Échec de la sauvegarde !");
    // }

    public function run(DatabaseConnection $database)
{
    $timestamp  = now()->format('Ymd_His');
    $safeDbname = preg_replace('/[^A-Za-z0-9_\-]/', '_', $database->dbname ?? 'db');
    $filename   = "{$safeDbname}_{$timestamp}.sql";
    $backupDir  = storage_path("app/backups");
    $backupPath = $backupDir . DIRECTORY_SEPARATOR . $filename;

    // Crée le dossier si besoin (silencieux)
    if (!is_dir($backupDir)) {
        @mkdir($backupDir, 0775, true);
    }

    // Normalisation host/port dans Docker
    $type = $database->type;
    $host = $database->host ?: ($type === 'postgres' ? 'postgres' : 'mysql');
    if (in_array($host, ['127.0.0.1', 'localhost', '0.0.0.0'], true)) {
        $host = ($type === 'postgres') ? 'postgres' : 'mysql';
    }
    $port = (int)($database->port ?: ($type === 'postgres' ? 5432 : 3306));

    $user = (string)($database->username ?? 'root');
    $pass = (string)($database->password ?? '');
    $db   = (string)($database->dbname ?? '');

    $result = null;
    $output = [];

    if ($type === 'mysql') {
        // Mot de passe via env (pas dans la ligne de commande)
        $envPrefix = $pass !== '' ? 'MYSQL_PWD=' . escapeshellarg($pass) . ' ' : '';
        // --result-file évite la redirection shell
        $cmd = $envPrefix . sprintf(
            'mysqldump -h %s -P %d -u %s --single-transaction --quick %s --result-file=%s 2>&1',
            escapeshellarg($host),
            $port,
            escapeshellarg($user),
            escapeshellarg($db),
            escapeshellarg($backupPath)
        );
    } elseif ($type === 'postgres') {
        $envPrefix = $pass !== '' ? 'PGPASSWORD=' . escapeshellarg($pass) . ' ' : '';
        $cmd = $envPrefix . sprintf(
            'pg_dump -h %s -p %d -U %s -d %s -F p -f %s 2>&1',
            escapeshellarg($host),
            $port,
            escapeshellarg($user),
            escapeshellarg($db),
            escapeshellarg($backupPath)
        );
    } else {
        Log::error("Erreur backup: type de base inconnu", ['type' => $type]);
        return back()->with('error', '❌ Type de base non reconnu.');
    }

    // Exécution
    exec($cmd, $output, $result);

    if ($result !== 0) {
        Log::error('Erreur backup:', [
            'type'   => $type,
            'host'   => $host,
            'port'   => $port,
            'db'     => $db,
            'exit'   => $result,
            // on loggue l'output pour voir l'erreur exacte (sans exposer le mdp qui est en env)
            'output' => implode("\n", $output),
        ]);
    }

    Backup::create([
        'db_connection_id' => $database->id,
        'file_path'        => "backups/{$filename}",
        'status'           => $result === 0 ? 'success' : 'fail',
    ]);

    return back()->with(
        $result === 0 ? 'success' : 'error',
        $result === 0
            ? "✅ Sauvegarde réussie pour {$db}"
            : "❌ Échec de la sauvegarde (voir logs)."
    );
}


    public function index()
    {
        $backups = Backup::with('database')->latest()->paginate(10);
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return view('backups.index', compact('backups'));
        }

        return view('backups.readonly', compact('backups'));
    }

    public function download(Backup $backup)
    {
        $fullPath = storage_path('app/' . $backup->file_path);

        if (!file_exists($fullPath)) {
            return back()->with('error', '❌ Fichier introuvable : ' . $fullPath);
        }

        return response()->download($fullPath, basename($backup->file_path), [
            'Content-Type' => 'application/sql',
        ]);
    }

    public function restore(Backup $backup)
    {
        $database = $backup->database;
        $backupFile = storage_path('app/' . $backup->file_path);

        if (!file_exists($backupFile)) {
            return back()->with('error', '❌ Fichier de sauvegarde introuvable.');
        }

        if ($database->type === 'mysql') {
            $passwordOption = $database->password ? "-p'{$database->password}'" : '';
            $cmd = "mysql -h {$database->host} -u {$database->username} {$passwordOption} {$database->dbname} < \"{$backupFile}\"";
        } else {
            $passwordOption = $database->password ? "PGPASSWORD='{$database->password}' " : '';
            $cmd = "{$passwordOption}psql -h {$database->host} -U {$database->username} -d {$database->dbname} -f \"{$backupFile}\"";
        }

        $result = null;
        $output = null;
        exec($cmd, $output, $result);

        return back()->with($result === 0 ? 'success' : 'error', $result === 0
            ? '✅ Base restaurée avec succès !'
            : '❌ Échec de la restauration. Vérifie le fichier ou les droits.');
    }
}
