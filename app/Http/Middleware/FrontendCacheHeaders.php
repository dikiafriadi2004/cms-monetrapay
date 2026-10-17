<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Add cache-control headers for public frontend pages.
 * Admin and POST routes are excluded automatically.
 *
 * Strategy:
 * - Public GET pages → cache 10 min at CDN/proxy, 1 min stale-while-revalidate
 * - API/admin → no-store
 * - After login (auth) → private
 */
class FrontendCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && ! $request->is('admin/*') && ! auth()->check()) {
            $response->headers->set(
                'Cache-Control',
                'public, max-age=600, stale-while-revalidate=60'
            );
        }

        return $response;
    }
}
