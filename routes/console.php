<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\DatabaseConnection;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Planifier une sauvegarde automatique tous les jours à 2h du matin
Schedule::call(function () {
    $connections = DatabaseConnection::all();

    foreach ($connections as $db) {
        // Appeler la commande Artisan pour chaque connexion
        Artisan::call('backup:run', [
            'db_id' => $db->id
        ]);
    }

    info('✅ Sauvegarde automatique terminée à ' . now());
})->dailyAt('02:00');

