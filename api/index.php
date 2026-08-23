<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ensure /tmp directories exist for Laravel on Vercel
$dirs = [
    '/tmp/views',
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// If running in Vercel and using SQLite, copy database to /tmp if needed
$dbConn = $_ENV['DB_CONNECTION'] ?? $_SERVER['DB_CONNECTION'] ?? getenv('DB_CONNECTION') ?: 'sqlite';
if ((isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) && $dbConn === 'sqlite') {
    $sqliteSrc = __DIR__ . '/../database/database.sqlite';
    $sqliteDst = '/tmp/database.sqlite';
    if (file_exists($sqliteSrc) && !file_exists($sqliteDst)) {
        @copy($sqliteSrc, $sqliteDst);
    }
}

try {
    require __DIR__ . '/../vendor/autoload.php';

    /** @var \Illuminate\Foundation\Application $app */
    $app = require __DIR__ . '/../bootstrap/app.php';

    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    
    // Explicitly bootstrap to catch any configuration/provider errors
    $kernel->bootstrap();

    $request = \Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "=== Laravel Bootstrap/Runtime Error on Vercel ===\n\n";
    echo "Class: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString();
}