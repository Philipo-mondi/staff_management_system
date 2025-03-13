<?php
include 'db.php';

$sql = "SELECT * FROM staff";
$result = $conn->query($sql);

$staff = [];
while ($row = $result->fetch_assoc()) {
    $staff[] = $row;
}

echo json_encode($staff);
?>
