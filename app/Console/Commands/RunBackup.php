<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DatabaseConnection;
use App\Models\Backup;
use Illuminate\Support\Facades\Storage;

class RunBackup extends Command
{
    protected $signature = 'backup:run {db_id}';
    protected $description = 'Exécute une sauvegarde d’une base spécifique';

    public function handle()
    {
        $db = DatabaseConnection::find($this->argument('db_id'));

        if (!$db) {
            $this->error("Connexion introuvable !");
            return;
        }

        // Chemin du fichier
        $timestamp = now()->format('Ymd_His');
        $filename = "{$db->dbname}_{$timestamp}.sql";
        $backupPath = storage_path("app/backups/{$filename}");

        // Commande en fonction du type
        if ($db->type === 'mysql') {
            $cmd = "mysqldump -h {$db->host} -u {$db->username} -p'{$db->password}' {$db->dbname} > {$backupPath}";
        } else {
            $cmd = "PGPASSWORD='{$db->password}' pg_dump -h {$db->host} -U {$db->username} -d {$db->dbname} > {$backupPath}";
        }

        $result = null;
        $output = null;
        exec($cmd, $output, $result);

        // Enregistrer le résultat
        Backup::create([
            'db_connection_id' => $db->id,
            'file_path' => "backups/{$filename}",
            'status' => $result === 0 ? 'success' : 'fail',
        ]);

        if ($result === 0) {
            $this->info("✅ Sauvegarde réussie : {$filename}");
        } else {
            $this->error("❌ Échec de la sauvegarde !");
        }
    }
}

