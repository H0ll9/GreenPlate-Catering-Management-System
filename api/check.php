<?php
header('Content-Type: application/json');
require_once '../config/db.php';

// Get phone number from URL parameter ?phone=...
if (isset($_GET['phone'])) {
    $phone = $conn->real_escape_string($_GET['phone']);
    
    // Search for all requests with this phone number
    $sql = "SELECT * FROM requests WHERE contact_number = '$phone' ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $requests = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    }
    
    echo json_encode($requests);
} else {
    echo json_encode(["status" => "error", "message" => "Phone number required"]);
}

 $conn->close();
?>