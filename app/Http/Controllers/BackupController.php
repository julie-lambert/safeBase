<?php

namespace App\Http\Controllers;

use App\Models\DatabaseConnection;
use App\Models\Backup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    public function run(DatabaseConnection $database)
    {
        // Chemin du fichier
        $timestamp = now()->format('Ymd_His');
        $filename = "{$database->dbname}_{$timestamp}.sql";
        $backupPath = storage_path("app/backups/{$filename}");


            // Construire la commande en fonction du type de base
            if ($database->type === 'mysql') {
                // Si mot de passe vide, on n'ajoute pas -p
                $passwordOption = $database->password ? "-p'{$database->password}'" : '';
                $cmd = "mysqldump -h {$database->host} -u {$database->username} {$passwordOption} {$database->dbname} > {$backupPath}";
            } else {
                // Pour PostgreSQL, on passe le mot de passe via la variable d'env
                $passwordOption = $database->password ? "PGPASSWORD='{$database->password}' " : '';
                $cmd = "{$passwordOption}pg_dump -h {$database->host} -U {$database->username} -d {$database->dbname} > {$backupPath}";
            }

            // Exécuter la commande
            $result = null;
            $output = null;
            exec($cmd, $output, $result);


        // Enregistrement dans backups
        Backup::create([
            'db_connection_id' => $database->id,
            'file_path' => "backups/{$filename}",
            'status' => $result === 0 ? 'success' : 'fail',
        ]);

        if ($result === 0) {
            return back()->with('success', "✅ Sauvegarde réussie pour {$database->dbname}");
        } else {
            return back()->with('error', "❌ Échec de la sauvegarde !");
        }
    }

     // ✅ Page liste des sauvegardes
public function index()
{
    $backups = Backup::with('database')->latest()->paginate(10);

    $user = Auth::user(); // ✅ Toujours fiable

    if ($user && $user->role === 'admin') {
        return view('backups.index', compact('backups')); // vue admin
    }

    return view('backups.readonly', compact('backups')); // vue lecture seule
}


    // ✅ Télécharger un fichier de sauvegarde
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
        // Chemin vers mysql.exe
        $mysqlPath = 'C:/wamp64/bin/mysql/mysql8.2.0/bin/mysql.exe';

        // Option mot de passe (facultatif)
        $passwordOption = $database->password ? "-p{$database->password}" : '';

        // Construction de la commande
        $cmd = "\"{$mysqlPath}\" -h {$database->host} -u {$database->username} {$passwordOption} {$database->dbname} < \"{$backupFile}\"";
    } else {
        // Pour PostgreSQL à adapter
        return back()->with('error', '❌ Restauration PostgreSQL non configurée.');
    }

    // ✅ Exécuter sous Windows via CMD
    exec("cmd /c {$cmd}", $output, $result);

    if ($result === 0) {
        return back()->with('success', '✅ Base restaurée avec succès !');
    } else {
        return back()->with('error', '❌ Échec de la restauration. Vérifie le fichier ou les droits.');
    }
}



}
