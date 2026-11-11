<?php

use App\Http\Middleware\ApiRateLimit;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\FrontendCacheHeaders;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SentryContext;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Sentry\Laravel\Integration;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/login');

        // Trust proxies (for HTTPS detection behind load balancers)
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            CheckMaintenanceMode::class,
            SecurityHeaders::class,
            SentryContext::class,
            FrontendCacheHeaders::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'check.permission' => CheckPermission::class,
            'api.rate' => ApiRateLimit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Send unhandled exceptions to Sentry (only in production/staging)
        Integration::handles($exceptions);

        // Don't send PII-sensitive 4xx exceptions
        $exceptions->dontReport([
            AuthenticationException::class,
            AuthorizationException::class,
            ValidationException::class,
            TokenMismatchException::class,
            NotFoundHttpException::class,
            MethodNotAllowedHttpException::class,
            ThrottleRequestsException::class,
        ]);
    })->create();
