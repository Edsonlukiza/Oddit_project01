<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags(trim($_POST["message"])));

    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('Tafadhali jaza nafasi zote.'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email uliyoweka si sahihi.'); window.history.back();</script>";
        exit;
    }

    try {
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)";
        $stmt = $conn->prepare($sql);
        
        $params = [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];

        if ($stmt->execute($params)) {
            echo "<script>
                alert('Thank you, $name! Your message has been received and saved.');
                window.location.href='oddit.html'; 
            </script>";
        }
    } catch(PDOException $e) {
        echo "<div style='color:red; background:#fee; padding:20px; border:1px solid red; font-family:sans-serif;'>";
        echo "<h3>Database Error!</h3>";
        echo "Error details: <strong>" . $e->getMessage() . "</strong>";
        echo "<br><br><button onclick='window.history.back()'>Rudi Kwenye Fomu</button>";
        echo "</div>";
    }
} else {
    header("Location: oddit.html");
    exit();
}
?>