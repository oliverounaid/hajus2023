<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->is_admin) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
