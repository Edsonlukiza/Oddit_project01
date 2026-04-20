<?php

$conn = mysqli_connect('localhost', 'root', '', 'oddit_db');

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
?>
