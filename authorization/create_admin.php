<?php
include __DIR__ . '/../backend/config/db_connect.php';

// Admin details
$name = "developer1";
$email = "developer1@example.com";
$password = "developer";

// IMPORTANT: role is STRING in your DB
$role_id = "admin";

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert admin
$sql = "INSERT INTO users (name, email, password, role_id)
        VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "ssss",   // all strings
    $name,
    $email,
    $hashed_password,
    $role_id
);

if (mysqli_stmt_execute($stmt)) {
    echo "Admin created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
