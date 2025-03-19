<?php
include "db.php";

header("Content-Type: application/json");

$sql = "SELECT * FROM staff";
$result = $conn->query($sql);

$staff = [];
while ($row = $result->fetch_assoc()) {
    $staff[] = $row;
}

echo json_encode($staff);
$conn->close();
?>
