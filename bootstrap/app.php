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
        // Alias untuk middleware restore sesi impersonasi admin.
        // Middleware ini didaftarkan sebagai route middleware (bukan web group global)
        // agar SELALU berjalan SETELAH middleware `auth` menyelesaikan autentikasi
        // (termasuk auto-login via remember token). Ini memastikan Auth::user()
        // sudah terisi sebelum kita mencoba membaca impersonated_by_admin_id dari DB.
        $middleware->alias([
            'restore.impersonation' => \App\Http\Middleware\RestoreImpersonationSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
