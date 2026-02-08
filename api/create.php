<?php
header('Content-Type: application/json');
require_once '../config/db.php';

// Receive JSON input
 $data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name) && 
    !empty($data->contact) && 
    !empty($data->date) && 
    !empty($data->time) && 
    !empty($data->location) &&
    !empty($data->type) && 
    !empty($data->people)
) {
    $request_id = 'REQ-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    $stmt = $conn->prepare("INSERT INTO requests (request_id, customer_name, contact_number, event_date, event_time, location, event_type, num_people, instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        "sssssssis", 
        $request_id,
        $data->name,
        $data->contact,
        $data->date,
        $data->time,
        $data->location,
        $data->type,
        $data->people,
        $data->instructions
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "id" => $request_id]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data"]);
}

 $conn->close();
?>