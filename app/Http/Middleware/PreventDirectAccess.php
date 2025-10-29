<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow AJAX requests and API calls
        if ($request->ajax() || $request->expectsJson()) {
            return $next($request);
        }

        // Allow if coming from within the application
        $referer = $request->headers->get('referer');
        $appUrl = config('app.url');
        
        if ($referer && str_starts_with($referer, $appUrl)) {
            return $next($request);
        }

        // Allow if it's the first visit to dashboard (after login)
        if (in_array($request->route()->getName(), [
            'dashboard.admin', 
            'dashboard.operario', 
            'dashboard.estandar'
        ])) {
            return $next($request);
        }

        // Redirect to appropriate dashboard for direct URL access
        $user = Auth::user();
        if ($user) {
            switch ($user->rol) {
                case 'admin':
                    return redirect()->route('dashboard.admin')->with('warning', 'Acceso directo no permitido. Navega usando el menú.');
                case 'operario':
                    return redirect()->route('dashboard.operario')->with('warning', 'Acceso directo no permitido. Navega usando el menú.');
                case 'estandar':
                    return redirect()->route('dashboard.estandar')->with('warning', 'Acceso directo no permitido. Navega usando el menú.');
            }
        }

        return redirect()->route('login');
    }
}