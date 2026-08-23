<?php
/**
 * Login API Endpoint
 * Method: POST
 * Body: { email, password }
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Gunakan POST.']);
    exit;
}

$data = getRequestBody();

$email = strtolower(trim($data['email'] ?? ''));
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email dan password wajib diisi!']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Email atau password salah.']);
        exit;
    }

    // Verifikasi password (Bcrypt / Argon2 / fallback)
    $passwordValid = password_verify($password, $user['password']);
    if (!$passwordValid && ($user['password'] === $password || $user['password'] === md5($password))) {
        $passwordValid = true;
    }

    if (!$passwordValid) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Email atau password salah.']);
        exit;
    }

    // Hilangkan field password dari output
    unset($user['password']);
    unset($user['remember_token']);

    echo json_encode([
        'success' => true,
        'message' => 'Login berhasil!',
        'user' => [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'phone' => $user['phone'] ?? null
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada database: ' . $e->getMessage()
    ]);
}
