<?php

namespace App\Http\Controllers;

use App\Models\DatabaseConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseConnectionController extends Controller
{
    public function index()
    {
        $databases = DatabaseConnection::all();
        return view('databases.index', compact('databases'));
    }

    public function create()
    {
        return view('databases.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:mysql,pgsql',
            'host' => 'required|string',
            'dbname' => 'required|string',
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);

        DatabaseConnection::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'host' => $request->host,
            'dbname' => $request->dbname,
            'username' => $request->username,
            'password' => $request->password ?? '',
        ]);

        return redirect()->route('databases.index')->with('success', 'Connexion ajoutée !');
    }

    public function edit(DatabaseConnection $database)
    {
        return view('databases.edit', compact('database'));
    }

    public function update(Request $request, DatabaseConnection $database)
    {
        $request->validate([
            'type' => 'required|in:mysql,pgsql',
            'host' => 'required|string',
            'dbname' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $database->update($request->all());

        return redirect()->route('databases.index')->with('success', 'Connexion mise à jour !');
    }

    public function destroy(DatabaseConnection $database)
    {
        $database->delete();
        return redirect()->route('databases.index')->with('success', 'Connexion supprimée !');
    }
}
