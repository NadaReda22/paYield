<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
       if (!Auth::check()) {
        abort(403, 'Unauthorized');
    }

    $userRole = Auth::user()->role;

    if (!in_array($userRole, $allowedRoles)) {
        abort(403, 'Access denied for your role.');
    }
       return $next($request);
    }
}
