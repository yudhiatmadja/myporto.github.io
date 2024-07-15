<?php
require 'config.php';

// Data for the new admin user
$username = 'admin';
$password = password_hash('admin_password', PASSWORD_DEFAULT);

// Insert new user into the database
$sql = "INSERT INTO user (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "New admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
