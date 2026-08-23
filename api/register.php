<?php
/**
 * Register API Endpoint
 * Method: POST
 * Body: { name, email, password, role }
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Gunakan POST.']);
    exit;
}

$data = getRequestBody();

$name = trim($data['name'] ?? '');
$email = strtolower(trim($data['email'] ?? ''));
$password = trim($data['password'] ?? '');
$role = trim($data['role'] ?? 'pelanggan');
$phone = trim($data['phone'] ?? null);

// Validasi
if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nama, email, dan password wajib diisi!']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid!']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password minimal 6 karakter!']);
    exit;
}

if (!in_array($role, ['pelanggan', 'penyedia', 'admin'])) {
    $role = 'pelanggan';
}

try {
    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar! Silakan gunakan email lain atau login.']);
        exit;
    }

    // Hash password menggunakan bcrypt (kompatibel dengan Laravel)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $now = date('Y-m-d H:i:s');

    // Insert user baru
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role, phone, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $email, $hashedPassword, $role, $phone, $now, $now]);

    $userId = (int) $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Registrasi berhasil!',
        'user' => [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'phone' => $phone,
            'created_at' => $now
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()
    ]);
}
