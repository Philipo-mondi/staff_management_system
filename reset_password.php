<?php
include 'db.php';

$username = 'admin';  
$new_password = 'admin123';  

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $hashed_password, $username);

if ($stmt->execute()) {
    echo "Password reset successfully! You can now log in with: <br>";
    echo "Username: " . $username . "<br>";
    echo "Password: " . $new_password;
} else {
    echo "Error updating password.";
}
?>
