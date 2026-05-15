<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
   public function handle(Request $request, Closure $next, $role): Response
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    if (strtolower($user->role) !== strtolower($role)) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    return $next($request);
}
}
