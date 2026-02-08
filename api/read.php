
<?php
header('Content-Type: application/json');
require_once '../config/db.php';

 $sql = "SELECT * FROM requests ORDER BY created_at DESC";
 $result = $conn->query($sql);

 $requests = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

echo json_encode($requests);
 $conn->close();
?>