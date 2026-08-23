<?php
/**
 * Test Connection & Database Tables
 */
require_once __DIR__ . '/config.php';

try {
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Check user count
    $userCount = 0;
    if (in_array('users', $tables)) {
        $stmtUsers = $pdo->query("SELECT COUNT(*) FROM users");
        $userCount = (int) $stmtUsers->fetchColumn();
    }

    echo json_encode([
        'success' => true,
        'message' => 'Koneksi ke database InfinityFree berhasil!',
        'database' => $dbName,
        'tables_count' => count($tables),
        'tables' => $tables,
        'users_count' => $userCount,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
