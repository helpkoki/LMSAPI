<?php
header('Content-Type: application/json');
require_once 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Fetch user
$sql = "SELECT id, email, full_name, role, is_active FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
}

// Fake password check (for demo – replace with real password check)
if ($password !== 'demo123') {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
}

// Success response
echo json_encode([
    'message' => 'Login successful',
    'user' => $user
]);
