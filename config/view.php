<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        ((is_dir('/tmp/views') || isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']))
            ? '/tmp/views'
            : (realpath(storage_path('framework/views')) ?: storage_path('framework/views')))
    ),

];
