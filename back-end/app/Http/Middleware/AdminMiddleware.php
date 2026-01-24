<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!method_exists($user, 'isAdmin') || !$user->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
