<?php

namespace App\Http\Controllers;

use App\Models\DatabaseConnection;
use App\Models\Backup;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $connectionsCount = DatabaseConnection::count();
        $backupsCount = Backup::count();

        // 5 dernières sauvegardes
        $recentBackups = Backup::with('database')->latest()->take(5)->get();

        return view('admin.dashboard', compact('connectionsCount', 'backupsCount', 'recentBackups'));
    }
}

