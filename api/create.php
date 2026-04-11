<?php
header('Content-Type: application/json');
require_once '../config/db.php';

 $data = json_decode(file_get_contents("php://input"));

// Validation: Ensure main fields exist
if (
    !empty($data->name) && 
    !empty($data->contact) && 
    !empty($data->date) && 
    !empty($data->location) &&
    !empty($data->people)
) {
    $request_id = 'REQ-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // 1. Convert Menu Array to JSON
    $menu_json = isset($data->menu) ? json_encode($data->menu) : json_encode([]);
    
    // 2. Update SQL Query to include selected_menu
    // Note: You must have added the column in the database (Step 1 of previous response)
    $stmt = $conn->prepare("INSERT INTO requests (request_id, customer_name, contact_number, event_date, event_time, location, event_type, num_people, instructions, selected_menu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        "ssssssssss", 
        $request_id,
        $data->name,
        $data->contact,
        $data->date,
        $data->time,
        $data->location,
        $data->type,
        $data->people,
        $data->instructions,
        $menu_json
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "id" => $request_id]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete data received. Check fields."]);
}

 $conn->close();
?>