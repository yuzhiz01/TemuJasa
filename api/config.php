<?php
/**
 * TemuJasa - InfinityFree Database API Configuration
 * CORS & Database Connection Handler
 */

// Allow CORS from any origin (GitHub Pages, Vercel, Localhost, etc.)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// InfinityFree Database Credentials
$dbHost = 'sql101.infinityfree.com'; // Sesuai host database di InfinityFree Control Panel
$dbName = 'if0_42725786_temujasa';   // Nama database
$dbUser = 'if0_42725786';            // Username database
$dbPass = 'Ciachinsiang00';          // Password akun InfinityFree

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal terhubung ke database InfinityFree: ' . $e->getMessage()
    ]);
    exit;
}

/**
 * Helper to get JSON or POST request body
 */
function getRequestBody() {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if (is_array($data)) {
        return $data;
    }
    return $_POST;
}
