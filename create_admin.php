<?php
include 'db.php';

$username = "admin";
$password = "admin123"; // Default password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = "admin"; // Assign the role as admin

// Insert admin user
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $role);

if ($stmt->execute()) {
    echo "Admin user created successfully!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "Role: Admin";
} else {
    echo "Error creating admin user: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
