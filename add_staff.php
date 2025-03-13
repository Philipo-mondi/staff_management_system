<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $id_number = $_POST['id_number'];
    $country = $_POST['country'];
    $language_spoken = $_POST['language_spoken'];  // Corrected variable name

    if (empty($name) || empty($id_number) || empty($country) || empty($language_spoken)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $sql = "INSERT INTO staff (name, id_number, country, language_spoken) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $id_number, $country, $language_spoken);  // Corrected variable name
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Staff added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database insert failed: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
