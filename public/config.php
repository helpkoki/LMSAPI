<?php
header("Content-Type: application/json");

// Database credentials
$host = "localhost";
$db_name = "api";
$db_user = "root";
$db_pass = "";

// Create connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}
?>
