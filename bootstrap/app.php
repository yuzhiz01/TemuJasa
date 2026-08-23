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
        // Vercel menjalankan aplikasi di balik proxy — wajib agar
        // URL, redirect, dan secure cookie terdeteksi sebagai HTTPS.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role.pelanggan' => \App\Http\Middleware\RolePelanggan::class,
            'role.penyedia'  => \App\Http\Middleware\RolePenyedia::class,
            'role.admin'     => \App\Http\Middleware\RoleAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
