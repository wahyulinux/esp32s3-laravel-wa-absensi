<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Hanya admin yang diizinkan.'], 403);
            }

            abort(403, 'Hanya admin yang diizinkan mengakses halaman ini.');
        }

        return $next($request);
    }
}
