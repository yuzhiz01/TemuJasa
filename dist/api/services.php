<?php
/**
 * Services & Categories API Endpoint
 * Method: GET
 */
require_once __DIR__ . '/config.php';

try {
    // Get Categories
    $stmtCat = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
    $categories = $stmtCat->fetchAll();

    // Get Active Services with Provider Info
    $sql = "
        SELECT 
            s.*,
            u.name as provider_name,
            u.email as provider_email,
            c.name as category_name
        FROM services s
        LEFT JOIN users u ON s.provider_id = u.id
        LEFT JOIN categories c ON s.category_id = c.id
        WHERE s.is_active = 1
        ORDER BY s.id DESC
    ";
    $stmtServices = $pdo->query($sql);
    $services = $stmtServices->fetchAll();

    echo json_encode([
        'success' => true,
        'categories' => $categories,
        'services' => $services
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengambil data layanan: ' . $e->getMessage()
    ]);
}
