<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sentry\State\Scope;
use Symfony\Component\HttpFoundation\Response;

use function Sentry\configureScope;

/**
 * Attach authenticated user info + request context to outgoing Sentry events.
 * Skipped silently if Sentry isn't configured.
 */
class SentryContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('sentry.dsn')) {
            return $next($request);
        }

        configureScope(function (Scope $scope) use ($request): void {
            // User context (anonymized — only id + email-domain by default)
            if ($user = $request->user()) {
                $scope->setUser([
                    'id' => (string) $user->getKey(),
                    'username' => $user->name ?? null,
                    'email' => config('sentry.send_default_pii', false) ? $user->email : null,
                    'role' => method_exists($user, 'roles') ? optional($user->roles->first())->name : null,
                ]);
            }

            // Request context
            $scope->setTag('route', optional($request->route())->getName() ?? 'unknown');
            $scope->setTag('http.method', $request->method());

            // Path tag (useful for filtering)
            $scope->setTag('http.path_prefix', $this->extractPrefix($request->path()));
        });

        return $next($request);
    }

    protected function extractPrefix(string $path): string
    {
        $first = strtok($path, '/');

        return $first === false ? 'root' : $first;
    }
}
