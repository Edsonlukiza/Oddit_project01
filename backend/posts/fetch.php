<?php
include __DIR__ . '/../config/db_connect.php';

$query = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$posts = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
}

echo json_encode($posts);
?>