<?php
// signup.php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'department', 'designation', 'email', 'password'];
    
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "All fields are required"]);
            exit;
        }
    }
    
    // Sanitize inputs
    $first_name = mysqli_real_escape_string($con, trim($input['first_name']));
    $last_name = mysqli_real_escape_string($con, trim($input['last_name']));
    $department = mysqli_real_escape_string($con, trim($input['department']));
    $designation = mysqli_real_escape_string($con, trim($input['designation']));
    $email = mysqli_real_escape_string($con, trim($input['email']));
    $password = $input['password'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    
    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $check_email);
    
    if (mysqli_num_rows($result) > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into database
    $query = "INSERT INTO users (first_name, last_name, department, designation, email, password) 
              VALUES ('$first_name', '$last_name', '$department', '$designation', '$email', '$hashed_password')";
    
    if (mysqli_query($con, $query)) {
        http_response_code(201);
        echo json_encode([
            'success' => true, 
            'message' => 'User registered successfully',
            'user_id' => mysqli_insert_id($con)
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . mysqli_error($con)]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

mysqli_close($con);
?>

<?php
// login.php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($input['email']) || empty($input['password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        exit;
    }
    
    // Sanitize inputs
    $email = mysqli_real_escape_string($con, trim($input['email']));
    $password = $input['password'];
    
    // Find user by email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Remove password from response
            unset($user['password']);
            
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

mysqli_close($con);
?>