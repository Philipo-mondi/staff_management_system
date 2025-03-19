<?php
include 'db.php';

// First Admin User
$username1 = "admin";
$password1 = "admin123"; // Default password
$hashed_password1 = password_hash($password1, PASSWORD_DEFAULT);
$role1 = "admin";

// Second User
$username2 = "Staff";
$password2 = "staff123"; // Default password
$hashed_password2 = password_hash($password2, PASSWORD_DEFAULT);
$role2 = "staff";

// Insert first admin user
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username1, $hashed_password1, $role1);
$stmt->execute();

// Insert second staff user
$stmt->bind_param("sss", $username2, $hashed_password2, $role2);
$stmt->execute();

if ($stmt) {
    echo "Users created successfully!<br>";
    echo "Admin - Username: admin | Password: admin123 | Role: Admin <br>";
    echo "Staff - Username: staff_user | Password: staff123 | Role: Staff";
} else {
    echo "Error creating users: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
