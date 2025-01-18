<?php 

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wars_and_warriors";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

?>