<?php
header('Content-Type: application/json');
require_once '../config/db.php';

 $data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->rating) && !empty($data->message)) {
    
    $stmt = $conn->prepare("INSERT INTO feedbacks (customer_name, rating, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $data->name, $data->rating, $data->message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Thank you for your feedback!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save feedback"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Please fill all fields"]);
}

 $conn->close();
?>