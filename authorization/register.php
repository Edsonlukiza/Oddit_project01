<?php
session_start();
include __DIR__ . '/../backend/config/db_connect.php';

$message = '';

// Handle form submission
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    $username = trim( $_POST[ 'name' ] );
    $email = trim( $_POST[ 'email' ] );
    $password = $_POST[ 'password' ];
    $confirm_password = $_POST[ 'confirm_password' ];

    if ( $password !== $confirm_password ) {
        $message = 'Passwords do not match!';
    } else {
        $stmt = $conn->prepare( 'SELECT user_id FROM users WHERE email = ?' );
        $stmt->bind_param( 's', $email );
        $stmt->execute();
        $stmt->store_result();

        if ( $stmt->num_rows > 0 ) {
            $message = 'Email already registered!';
        } else {
            $hashed_password = password_hash( $password, PASSWORD_BCRYPT );
            $role_id = 2;
            // student

            $stmt = $conn->prepare( 'INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, ?)' );
            $stmt->bind_param( 'sssi', $username, $email, $hashed_password, $role_id );
            $message = "Registration successful! <a href='login.php'>Login here</a>";

            $stmt->execute();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang = 'en'>
<head>
<meta charset = 'UTF-8'>
<meta name = 'viewport' content = 'width=device-width, initial-scale=1.0'>
<title>Register</title>
<link rel = 'stylesheet' type = 'text/css' href = 'registration.css'>
</head>
<body>
<div id = 'form'>
<h1>ODDITY</h1>
<div class = 'heading-divider'></div>
<?php
if ( isset( $_SESSION[ 'error' ] ) ) {
    echo "<p class='red'>" . $_SESSION[ 'error' ] . '</p>';
    unset( $_SESSION[ 'error' ] );
}
if ( isset( $_SESSION[ 'success' ] ) ) {
    echo "<p class='green'>" . $_SESSION[ 'success' ] . '</p>';
    unset( $_SESSION[ 'success' ] );
}
if ( $message ) {
    if ( strpos( $message, 'successful' ) !== false ) {
        echo "<p class='green'>" . $message . '</p>';
    } else {
        echo "<p class='red'>" . $message . '</p>';
    }
}
?>
<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = 'POST'>
<label for = 'name'>Full Name</label><br>
<input type = 'text' id = 'name' name = 'name' placeholder = 'John Doe' required><br>
<label for = 'email'>Email</label><br>
<input type = 'email' id = 'email' name = 'email' placeholder = 'you@oddity.tech' required><br>
<label for = 'password'>Password</label><br>
<input type = 'password' id = 'password' name = 'password' placeholder = '••••••••' required><br>
<label for = 'confirm_password'>Confirm Password</label><br>
<input type = 'password' id = 'confirm_password' name = 'confirm_password' placeholder = '••••••••' required><br><br>
<button type = 'submit'>Register</button>
</form>
<p>Already have an account? <a href = 'login.php'>Login here</a></p>
</div>
</body>
</html>
</div>
</body>
</html>
