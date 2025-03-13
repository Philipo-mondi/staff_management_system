<?php
include 'db.php';

// Fetch staff data
$sql = "SELECT id, name, id_number, country, language_spoken FROM staff";
$result = $conn->query($sql);

$staff_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staff_data[] = $row;
    }
}

// Return data as JSON
echo json_encode($staff_data);

$conn->close();
?>
