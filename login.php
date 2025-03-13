<?php
session_start();  // Start session at the very top
header("Content-Type: application/json");

// Include database connection
require_once "db.php";

// Read JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received in login.php"]);
    exit;
}

$username = trim($data["username"] ?? "");
$password = trim($data["password"] ?? "");

if ($username === "" || $password === "") {
    echo json_encode(["status" => "error", "message" => "Username and password are required."]);
    exit;
}

// Check user credentials
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    echo json_encode(["status" => "success", "message" => "Login successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
}

$stmt->close();
$conn->close();
?>
