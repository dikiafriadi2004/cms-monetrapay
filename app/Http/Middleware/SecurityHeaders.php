<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add security-related response headers to every response.
     *
     * Headers added:
     * - X-Content-Type-Options: nosniff   → block MIME sniffing
     * - X-Frame-Options: SAMEORIGIN       → block clickjacking via iframe
     * - X-XSS-Protection: 1; mode=block   → legacy XSS filter
     * - Referrer-Policy                   → don't leak full URL when navigating away
     * - Permissions-Policy                → disable unused powerful APIs
     * - Strict-Transport-Security (HTTPS) → force HTTPS for 1 year
     * - Content-Security-Policy           → restrict allowed sources
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');
        // Prevent Herd/nginx from injecting live-reload scripts
        $response->headers->set('X-Accel-Buffering', 'no');
        // HSTS only on HTTPS to avoid breaking local dev
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content Security Policy
        // In local development: skip CSP entirely so Vite HMR (localhost:5173),
        // browser-sync, and any dev-only tools are never blocked.
        // In staging/production: apply strict CSP.
        if (! app()->isLocal()) {
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://www.googletagmanager.com https://www.google-analytics.com https://pagead2.googlesyndication.com",
                // style-src-elem is the directive browsers actually check for <link> and @import.
                // Setting both style-src (fallback) and style-src-elem (preferred) prevents the
                // "action blocked" warning seen when style-src-elem is absent.
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
                "style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
                "font-src 'self' data: https://fonts.gstatic.com",
                "img-src 'self' data: blob: https:",
                "connect-src 'self' https://www.google-analytics.com",
                "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com https://googleads.g.doubleclick.net",
                "frame-ancestors 'self'",
                "base-uri 'self'",
                "form-action 'self'",
                "object-src 'none'",
            ];
            $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        }

        // Don't leak server info
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
