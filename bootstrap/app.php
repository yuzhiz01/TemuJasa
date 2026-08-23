<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Vercel menjalankan aplikasi di balik proxy
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role.pelanggan' => \App\Http\Middleware\RolePelanggan::class,
            'role.penyedia'  => \App\Http\Middleware\RolePenyedia::class,
            'role.admin'     => \App\Http\Middleware\RoleAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || config('app.debug')) {
                return response(
                    "=== Laravel Error Details ===\n\n" .
                    "Class: " . get_class($e) . "\n" .
                    "Message: " . $e->getMessage() . "\n" .
                    "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n" .
                    "Trace:\n" . $e->getTraceAsString(),
                    500,
                    ['Content-Type' => 'text/plain; charset=utf-8']
                );
            }
        });
    })->create();

// Ganti direktori storage ke /tmp jika berjalan di lingkungan serverless Vercel
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    $app->useStoragePath('/tmp/storage');
}

// Pastikan core providers selalu terdaftar
$app->register(\Illuminate\View\ViewServiceProvider::class);
$app->register(\Illuminate\Session\SessionServiceProvider::class);
$app->register(\Illuminate\Database\DatabaseServiceProvider::class);

return $app;
