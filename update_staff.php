<?php
include "db.php"; // Database connection

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $idNumber = $_POST["idNumber"];
    $country = $_POST["country"];
    $language = $_POST["language"];

    if (empty($id) || empty($name) || empty($idNumber) || empty($country) || empty($language)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE staff SET name=?, id_number=?, country=?, language_spoken=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $idNumber, $country, $language, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed"]);
    }

    $stmt->close();
    $conn->close();
}
?>
