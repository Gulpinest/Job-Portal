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
        //
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'admin' => \App\Http\Middleware\admin::class,
            'company' => \App\Http\Middleware\company::class,
            'pelamar' => \App\Http\Middleware\pelamar::class,
            // Middleware bawaan Breeze biasanya sudah otomatis terdaftar,
            // namun jika perlu, alias lain bisa ditambahkan di sini.
            // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            // 'auth' => \App\Http\Middleware\Authenticate::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/api/payment/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
