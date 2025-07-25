<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\DatabaseConnectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Dashboard commun à tout utilisateur connecté
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Profil (commun)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Lecture seule pour TOUS les utilisateurs connectés
    Route::get('/backups', [BackupController::class, 'index'])->name('user.backups.index');
    Route::get('/backups/download/{backup}', [BackupController::class, 'download'])->name('user.backups.download');
});

// ✅ Routes réservées aux ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Tableau de bord admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Gestion des connexions BDD
    Route::resource('/databases', DatabaseConnectionController::class)->except(['show']);

    // Sauvegarde manuelle d'une base
    Route::post('/databases/{database}/backup', [BackupController::class, 'run'])->name('databases.backup');

    // Historique complet (admin)
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
    Route::get('/backups/download/{backup}', [BackupController::class, 'download'])->name('backups.download');
    Route::post('/backups/restore/{backup}', [BackupController::class, 'restore'])->name('backups.restore');
});

require __DIR__.'/auth.php';
