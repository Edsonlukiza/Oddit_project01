<?php
/*session_start();

include __DIR__ . '/../backend/config/db_connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    $sql = "SELECT * FROM users WHERE name = '$user'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

      
        if (password_verify($pass, $row['password'])) {
           
            header("Location: /authorization/dashboard.php");
            exit();
        } else {
            echo "Invalid name or password.";
        }
    } else {
        echo "No user found with that name.";
    }
}


mysqli_close($conn);
?>*/
