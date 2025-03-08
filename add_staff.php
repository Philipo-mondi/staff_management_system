<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? null;
    $id_number = $_POST["id_number"] ?? null;
    $country = $_POST["country"] ?? null;
    $language_spoken = $_POST["language_spoken"] ?? null;
    error_log("Received: Name=$name, ID=$id_number, Country=$country, Language=$language_spoken");

    if ($name && $id_number && $country && $language_spoken) {
        $sql = "INSERT INTO staff (name, id_number, country, language_spoken) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $id_number, $country, $language_spoken);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Staff added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database insert failed"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
}
?>
