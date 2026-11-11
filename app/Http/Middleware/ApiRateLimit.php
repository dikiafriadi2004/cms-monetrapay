<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $limit = '60'): Response
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = (int) $limit;
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak permintaan. Silakan coba lagi dalam ' . $seconds . ' detik.',
                'retry_after' => $seconds,
            ], 429);
        }

        RateLimiter::hit($key, 60); // 60 seconds window

        $response = $next($request);

        // Add rate limit headers
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
        ]);

        return $response;
    }

    /**
     * Resolve the request signature
     */
    protected function resolveRequestSignature(Request $request): string
    {
        // Rate limit by user ID if authenticated, otherwise by IP
        if ($user = $request->user()) {
            return 'api:' . $user->id;
        }

        return 'api:' . $request->ip();
    }
}
