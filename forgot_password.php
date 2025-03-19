<?php
header("Content-Type: application/json");
session_start();
$conn = new mysqli("localhost", "root", "", "staff_management_system");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
}

// Get username from request
$username = $_POST["username"];

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Username is required."]);
    exit();
}

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found."]);
    exit();
}

// Generate a new default password
$new_password = "staff123";  // You can generate random passwords
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in database
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$update_stmt->bind_param("ss", $hashed_password, $username);

if ($update_stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password reset successfully!", "new_password" => $new_password]);
} else {
    echo json_encode(["status" => "error", "message" => "Password reset failed."]);
}
?>
