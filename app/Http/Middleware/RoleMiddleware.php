<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized.');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn ($roleParam) => explode(',', $roleParam))
            ->map(fn ($role) => trim($role))
            ->filter()
            ->all();

        if (!empty($allowedRoles) && !in_array(Auth::user()->role, $allowedRoles, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
