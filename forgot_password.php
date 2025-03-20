<?php
header("Content-Type: application/json");
session_start();
$conn = new mysqli("localhost", "root", "", "staff_management_system");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Debugging: Check if data is received correctly
if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received."]);
    exit();
}

$username = isset($data["username"]) ? trim($data["username"]) : "";

// Debugging: Check if username is received
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

// Generate new password
function generateRandomPassword($length = 12) {
    $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+";
    return substr(str_shuffle($characters), 0, $length);
}

$new_password = generateRandomPassword(12);
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in database
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$update_stmt->bind_param("ss", $hashed_password, $username);

if ($update_stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password reset successfully!", "new_password" => $new_password]);
} else {
    echo json_encode(["status" => "error", "message" => "Password reset failed."]);
}

$stmt->close();
$update_stmt->close();
$conn->close();
?>
