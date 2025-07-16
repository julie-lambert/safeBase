<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Accès interdit');
        }

        return $next($request);
    }
}

