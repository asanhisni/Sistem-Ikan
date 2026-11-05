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
    ->withMiddleware(function (Middleware $middleware) {
        // Alias untuk middleware restore sesi impersonasi admin
        $middleware->alias([
            'restore.impersonation' => \App\Http\Middleware\RestoreImpersonationSession::class,
        ]);

        // Tambahkan ke web middleware group agar berjalan di semua request web
        // (termasuk auto-login via remember token yang bypass halaman login)
        $middleware->web(append: [
            \App\Http\Middleware\RestoreImpersonationSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
