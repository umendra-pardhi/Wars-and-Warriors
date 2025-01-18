<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include('includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) ) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];

                echo json_encode(["status" => "success", "message" => "Login successful."]);
            } else {
                http_response_code(401); // Unauthorized
                echo json_encode(["status" => "error", "message" => "Invalid password."]);
            }
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["status" => "error", "message" => "User not found."]);
        }

        $stmt->close();
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "Email and password are required."]);
    }
}



$conn->close();
?>





