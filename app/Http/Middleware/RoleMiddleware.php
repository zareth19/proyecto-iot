<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleMiddleware
{
    /**
     * Verifica que el usuario autenticado tenga el rol especificado.
     */
    public function handle(Request $request, Closure $next, $role, $guard = null): Response
    {
        if (! $request->user()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (! $request->user()->hasRole($role)) {
            throw UnauthorizedException::forRoles([$role]);
        }

        return $next($request);
    }
}
