<?php
session_start();
include __DIR__ . '/../backend/config/db_connect.php';

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
   

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role_id = 2; // student

            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $role_id);
             $message = "Registration successful! <a href='login.php'>Login here</a>";

            $stmt->execute();
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="registration.css">
</head>
<body>
<div class="container">
    <div class="register">
        <h2>Register</h2>
        <?php 
        if (isset($_SESSION['error'])) {
            echo "<p class='red'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='green'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
        ?>
        <form method="POST">
 <?php if ($message): ?>
                    <div class="message"><?= $message ?></div>
                <?php endif; ?>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>

    </div>
</div>
</body>
</html>
