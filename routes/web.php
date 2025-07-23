<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\DatabaseConnectionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Routes réservées aux admins
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return "Bienvenue dans le panneau Admin";
    });
   Route::resource('databases', DatabaseConnectionController::class)->except(['show']);
Route::post('/databases/{database}/backup', [BackupController::class, 'run'])
    ->name('databases.backup');

 

Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
Route::get('/backups/download/{backup}', [BackupController::class, 'download'])->name('backups.download');

  Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

});

Route::post('/backups/restore/{backup}', [BackupController::class, 'restore'])
    ->name('backups.restore');


require __DIR__.'/auth.php';
