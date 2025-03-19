<?php
require 'db.php'; // Connect to MySQL

// Set response type
header("Content-Type: application/json");

// Get the request body
$data = json_decode(file_get_contents("php://input"), true);

// Check if all fields are provided
if (!isset($data["username"], $data["email"], $data["password"], $data["role"])) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

$username = trim($data["username"]);
$email = trim($data["email"]);
$password = trim($data["password"]);
$role = trim($data["role"]);

// ✅ Validate username (must contain letters + numbers)
if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $username)) {
    echo json_encode(["status" => "error", "message" => "Username must contain both letters and numbers."]);
    exit();
}

// ✅ Validate password (minimum 8 characters, contains letters + numbers)
if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d).{8,}$/', $password)) {
    echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters and contain letters and numbers."]);
    exit();
}

// ✅ Check if username or email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Username or email already taken."]);
    exit();
}
$stmt->close();

// ✅ Hash the password before storing it
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ✅ Insert new user into database
$stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Account created successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to register. Try again later."]);
}

$stmt->close();
$conn->close();
?>
