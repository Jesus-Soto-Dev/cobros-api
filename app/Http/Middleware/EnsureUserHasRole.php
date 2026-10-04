<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        $userRole = $user->role->value;

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'No tienes permisos para esta acción.');
        }

        return $next($request);
    }
}
