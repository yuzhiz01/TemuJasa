<?php
/**
 * TemuJasa - Entry Point
 * Mendukung hosting lokal (XAMPP / Apache / PHP Built-in Server) dan Server Produksi.
 */

// 1. Jika diakses melalui server statis / dist tersedia, arahkan ke dist
if (file_exists(__DIR__ . '/dist/index.html')) {
    header('Location: dist/');
    exit;
}

// 2. Jika diakses melalui Laravel backend (public)
if (file_exists(__DIR__ . '/public/index.php')) {
    require __DIR__ . '/public/index.php';
    exit;
}

// Fallback
echo "TemuJasa siap digunakan. Silakan buka folder /dist/ atau /public/.";
