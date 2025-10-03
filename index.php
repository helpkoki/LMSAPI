<?php
include 'config.php';

// Get the path from URL (e.g., /signup or /login)
$path = trim(str_replace("/API", "", $_SERVER['REQUEST_URI']), '/');


// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Helper function to respond with JSON
function response($success, $message, $extra = []) {
    echo json_encode(array_merge(["success" => $success, "message" => $message], $extra));
    exit;
}

switch ($path) {
    
    case 'signup':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response(false, "Only POST method allowed");
        }

        if(isset($data['username'], $data['email'], $data['password'])) {
            global $conn;
            $username = $conn->real_escape_string($data['username']);
            $email = $conn->real_escape_string($data['email']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);

            // Check if user exists
            $check = $conn->query("SELECT id FROM users WHERE email='$email' OR username='$username'");
            if($check->num_rows > 0){
                response(false, "User already exists");
            }

            // Insert user
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            if($conn->query($sql)){
                response(true, "User registered successfully");
            } else {
                response(false, "Registration failed");
            }
        } else {
            response(false, "Invalid input");
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            response(false, "Only POST method allowed");
        }

        if(isset($data['email'], $data['password'])){
            global $conn;
            $email = $conn->real_escape_string($data['email']);
            $password = $data['password'];

            $result = $conn->query("SELECT * FROM users WHERE email='$email'");
            if($result->num_rows == 1){
                $user = $result->fetch_assoc();
                if(password_verify($password, $user['password'])){
                    response(true, "Login successful", ["user" => [
                        "id" => $user['id'],
                        "username" => $user['username'],
                        "email" => $user['email']
                    ]]);
                } else {
                    response(false, "Incorrect password");
                }
            } else {
                response(false, "User not found");
            }
        } else {
            response(false, "Invalid input");
        }
        break;

    default:
        response(false, "Endpoint not found");
        break;
}
?>
