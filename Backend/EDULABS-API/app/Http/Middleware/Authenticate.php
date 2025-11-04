<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Para APIs, retornar null hace que Laravel devuelva JSON 401 automáticamente
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // Para rutas web (si las tienes), redirigir a login
        return '/login';
    }
}
