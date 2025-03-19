<?php
header("Content-Type: application/json");
session_start();
$conn = new mysqli("localhost", "root", "", "staff_management_system");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
$username = $data["username"];
$password = $data["password"];

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all fields."]);
    exit();
}

// Check if user exists and get role
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User doesn't exist."]);
    exit();
}

$user = $result->fetch_assoc();
$hashed_password = $user["password"];
$role = $user["role"]; // Get user role

// Check password
if (!password_verify($password, $hashed_password)) {
    echo json_encode(["status" => "error", "message" => "Incorrect password."]);
    exit();
}

// Store session data
$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];
$_SESSION["role"] = $role; // Store role in session

// Redirect based on role
if ($role === "admin") {
    echo json_encode(["status" => "success", "redirect" => "index.html"]);
} else {
    echo json_encode(["status" => "success", "redirect" => "staff_dashboard.html"]);
}
exit();
?>
