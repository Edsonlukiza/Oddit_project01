<?php
include __DIR__ . '/../../backend/config/db_connect.php';

// Fetch all sections
$query = "SELECT posts.*, post_categories.name AS section
          FROM posts
          JOIN post_categories ON posts.category_id = post_categories.id
          ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $query);

$sections = [
  'Home' => [],
  'About' => [],
  'Service' => [],
  'Contact' => [],
];

while ($row = mysqli_fetch_assoc($result)) {
  $sections[$row['section']][] = $row;
}
?>