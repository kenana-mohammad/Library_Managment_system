<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class checkAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                // (admin)،
                return $next($request);
            } else {
                // if user not  allowed
                return response()->json([
                    'error' => 'Only admins are allowed '
                ], 401);
            }
        }

        // اnauthorized
        return response()->json([
            'error' => 'Unauthenticated'
        ], 401);

    }}
