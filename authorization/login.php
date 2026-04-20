<?php
session_start();

include __DIR__ . '/../backend/config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row["password"])) {

        $_SESSION["user_id"] = $row["id"];
        $_SESSION["name"] = $row["name"];
        $_SESSION["role"] = $row["role_id"]; // admin or user

        if ($row["role_id"] === 'admin') {
           header("Location: ../backend/dashboard.php");
        } else {
            header("Location: ../frontend/index.php");
        }
        exit();
    } else {
        $_SESSION["error"] = "Invalid credentials.";
    }
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div id="form">
        <h1>Login</h1>

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

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="email">Email</label><br>
            <input type="text" id="email" name="email" required><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
