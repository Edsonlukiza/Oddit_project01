<?php
include __DIR__ . '/backend/config/db_connect.php';


// Get category from URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch posts
$sql = "SELECT posts.*, post_categories.name AS category_name
        FROM posts
        JOIN post_categories ON posts.category_id = post_categories.id";

if ($category) {
    $category = mysqli_real_escape_string($conn, $category);
    $sql .= " WHERE post_categories.name = '$category'";
}

$sql .= " ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Posts</title>

<style>
body{font-family:Segoe UI;padding:40px;background:#f9f9f9;}

.grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:25px;
}

.card{
  background:#fff;
  padding:20px;
  border-radius:12px;
  border:1px solid #eee;
}

.card img{
  width:100%;
  height:180px;
  object-fit:cover;
  border-radius:10px;
  margin-bottom:10px;
}

.about-split{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:30px;
  margin-bottom:40px;
}

.about-split img{
  width:100%;
  height:300px;
  object-fit:cover;
  border-radius:12px;
}

.service-card{
  display:flex;
  gap:20px;
  background:#fff;
  padding:20px;
  border-radius:12px;
  border:1px solid #eee;
  margin-bottom:20px;
}

.service-card img{
  width:120px;
  height:100px;
  object-fit:cover;
  border-radius:10px;
}
</style>
</head>

<body>

<h1><?= $category ? htmlspecialchars($category) : 'All Posts' ?></h1>

<?php if($category === 'About'): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="about-split">
            <img src="/Oddit/backend/posts/uploads/<?= $row['image'] ?>">
            <div>
                <h2><?= $row['title'] ?></h2>
                <p><?= $row['description'] ?></p>
            </div>
        </div>
    <?php endwhile; ?>

<?php elseif($category === 'Services'): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="service-card">
            <img src="/Oddit/backend/posts/uploads/<?= $row['image'] ?>">
            <div>
                <h3><?= $row['title'] ?></h3>
                <p><?= $row['excerpt'] ?></p>
            </div>
        </div>
    <?php endwhile; ?>

<?php else: ?>

    <div class="grid">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <img src="/Oddit/backend/posts/uploads/<?= $row['image'] ?>">
                <h3><?= $row['title'] ?></h3>
                <p><?= $row['excerpt'] ?></p>
            </div>
        <?php endwhile; ?>
    </div>

<?php endif; ?>

</body>
</html>