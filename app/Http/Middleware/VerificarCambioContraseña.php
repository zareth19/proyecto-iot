<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarCambioContraseña
{
    public function handle(Request $request, Closure $next)
    {
        // No aplicar middleware en rutas de logout o login
        if ($request->routeIs('logout') || $request->routeIs('login*')) {
            return $next($request);
        }
        
        if (Auth::check() && Auth::user()->debe_cambiar_contraseña) {
            if (!$request->routeIs('cambiar.contraseña*')) {
                return redirect()->route('cambiar.contraseña');
            }
        }

        return $next($request);
    }
}