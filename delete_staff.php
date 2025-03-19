<?php
include "db.php"; // Connect to the database

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];

    if (empty($id)) {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete staff"]);
    }

    $stmt->close();
    $conn->close();
}
?>
