<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Use the spread operator here
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if the user's role exists in the allowed roles array
        if (!in_array($request->user()->role, $roles)) {
            return response([
                'message' => 'Unauthorized. Access restricted to ' . implode(', ', $roles)
            ], 403);
        }

        return $next($request);
    }
}