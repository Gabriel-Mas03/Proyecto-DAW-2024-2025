<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global
        $middleware->prepend(\Illuminate\Cookie\Middleware\EncryptCookies::class);
        $middleware->append(\Illuminate\Session\Middleware\StartSession::class); // ✅ NECESARIO para sesiones
        $middleware->append(\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);
        $middleware->append(\Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $middleware->append(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);

        // Middleware específico para el grupo 'api'
        $middleware->appendToGroup('api', [
            EnsureFrontendRequestsAreStateful::class, // ✅ Convierte cookies en sesiones válidas
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
