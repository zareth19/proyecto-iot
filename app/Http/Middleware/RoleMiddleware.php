<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $userRole = strtolower(trim(Auth::user()->rol ?? ''));
        
        if ($userRole !== strtolower($role)) {
            abort(403, 'No tienes permisos para acceder a esta página');
        }

        return $next($request);
    }
}
