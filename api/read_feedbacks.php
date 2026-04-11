<?php
header('Content-Type: application/json');
require_once '../config/db.php';

 $sql = "SELECT * FROM feedbacks ORDER BY created_at DESC";
 $result = $conn->query($sql);

 $feedbacks = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}

echo json_encode($feedbacks);
 $conn->close();
?>