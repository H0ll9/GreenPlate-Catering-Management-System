
<?php
header('Content-Type: application/json');
require_once '../config/db.php';

 $data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->status)) {
    
    // Update Status in Database
    $updateStmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $updateStmt->bind_param("si", $data->status, $data->id);
    
    if ($updateStmt->execute()) {
        // Success - just return status
        echo json_encode(["status" => "success", "message" => "Status Updated Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database update failed"]);
    }
    $updateStmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

 $conn->close();
?>