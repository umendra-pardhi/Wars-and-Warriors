<?php
include('config/connection.php');

// Read the incoming request data
$data = json_decode(file_get_contents('php://input'), true);

// Check if the post ID is provided
if (isset($data['id'])) {
    $postId = $data['id'];

    // Prepare and execute the delete query
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Return failure response
        echo json_encode(['success' => false]);
    }
} else {
    // Return failure response if no ID is provided
    echo json_encode(['success' => false]);
}

$conn->close();
