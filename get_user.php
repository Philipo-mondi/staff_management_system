<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

// Return the username
echo json_encode(["status" => "success", "username" => $_SESSION["username"]]);
exit();
?>
