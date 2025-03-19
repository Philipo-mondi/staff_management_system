<?php
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (empty)
$database = "staff_management_system"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
