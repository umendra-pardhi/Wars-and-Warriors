<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(["status" => "error", "message" => "You are not authorized to view this page."]);
    exit;
}

// Database Connection
$db_hostname = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_name = "wars_and_warriors";

$conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the logged-in admin's email from session
$adminEmail = $_SESSION['email'];

// Handle Update Admin Details Request
if (isset($_POST['update_admin'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $currentPassword = $_POST['current_password'];

    // Check if current password is correct
    $result = $conn->query("SELECT password FROM users WHERE email = '$adminEmail'");
    $user = $result->fetch_assoc();
    
    if (password_verify($currentPassword, $user['password'])) {
        // Update the admin's details
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE email = ?");
        $stmt->bind_param("sss", $name, $email, $adminEmail);
        
        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            echo json_encode(["status" => "success", "message" => "Admin details updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update admin details."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Incorrect current password."]);
    }
}

// Handle Change Password Request
if (isset($_POST['update_password'])) {
    $currentPasswordModal = $_POST['current_password_modal'];
    $newPasswordModal = $_POST['new_password_modal'];

    // Check if current password is correct
    $result = $conn->query("SELECT password FROM users WHERE email = '$adminEmail'");
    $user = $result->fetch_assoc();

    if (password_verify($currentPasswordModal, $user['password'])) {
        // Hash the new password
        $hashedPassword = password_hash($newPasswordModal, PASSWORD_DEFAULT);
        
        // Update the password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $adminEmail);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Password changed successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to change password."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Incorrect current password."]);
    }
}

$conn->close();
?>
