<?php
header('Content-Type: application/json');
require_once '../config/db.php';

// Receive JSON input
 $data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    
    $stmt = $conn->prepare("DELETE FROM requests WHERE id = ?");
    $stmt->bind_param("i", $data->id);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete record"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No ID provided"]);
}

 $conn->close();
?>