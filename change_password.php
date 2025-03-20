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

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request. No data received."]);
    exit();
}

$username = isset($data["username"]) ? trim($data["username"]) : "";
$old_password = isset($data["old_password"]) ? trim($data["old_password"]) : "";
$new_password = isset($data["new_password"]) ? trim($data["new_password"]) : "";

// Check if all fields are filled
if (empty($username) || empty($old_password) || empty($new_password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

// Fetch the user's current password
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found."]);
    exit();
}

$user = $result->fetch_assoc();
$stored_password = $user["password"];

// Check if old password matches either the hashed stored password OR the reset password
if (!password_verify($old_password, $stored_password) && $old_password !== $stored_password) {
    echo json_encode(["status" => "error", "message" => "Old password is incorrect."]);
    exit();
}

// Hash new password before storing
$hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in database
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$update_stmt->bind_param("ss", $hashed_new_password, $username);

if ($update_stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password changed successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Password update failed."]);
}

$stmt->close();
$update_stmt->close();
$conn->close();
?>
