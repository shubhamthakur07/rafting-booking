<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

    $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Exclude contact route from CSRF verification
        $middleware->validateCsrfTokens(except: ['/contact']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (Throwable $e) {
        // This will bypass the broken error renderer and show the raw error
        // return response()->json([
        //     'message' => $e->getMessage(),
        //     'file' => $e->getFile(),
        //     'line' => $e->getLine(),
        // ], 500);
    });
})->create();
