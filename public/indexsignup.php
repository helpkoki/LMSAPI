<?php
header('Content-Type: application/json');
require_once 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);

// Basic validation
$required = ['first_name', 'last_name', 'department', 'designation', 'email', 'password'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing field: $field"]);
        exit;
    }
}

// Prepare data
$id = uuid_create(UUID_TYPE_RANDOM); // Requires ext-uuid
$full_name = $data['first_name'] . ' ' . $data['last_name'];
$email = $data['email'];
$password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
$role = 'employee'; // Default role
$department = $data['department'];
$is_active = true;

// Insert user
$sql = "INSERT INTO users (id, email, full_name, role, department_id, join_date, is_active, created_at, updated_at)
        VALUES (:id, :email, :full_name, :role, NULL, CURRENT_DATE, :is_active, NOW(), NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $id,
    ':email' => $email,
    ':full_name' => $full_name,
    ':role' => $role,
    ':is_active' => $is_active
]);

// You can also store password hash in a separate auth table if needed

echo json_encode(['message' => 'Signup successful']);
