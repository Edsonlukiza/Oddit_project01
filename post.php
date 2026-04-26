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
  flex-direction:column;
  gap:0;
  background:#fff;
  padding:0;
  border-radius:12px;
  border:1px solid #eee;
  margin-bottom:20px;
  overflow:hidden;
  transition:transform 0.3s, box-shadow 0.3s;
}

.service-card:hover{
  transform:translateY(-5px);
  box-shadow:0 8px 16px rgba(0,0,0,0.1);
}

.service-card img{
  width:100%;
  height:200px;
  object-fit:cover;
  border-radius:0;
}

.service-card > div{
  padding:20px;
}
</style>
</head>

<body>

<h1><?= $category ? htmlspecialchars($category) : 'All Posts' ?></h1>

<?php if($category === 'About'): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="about-split">
            <?php if (!empty($row['image'])): ?>
                <img src="backend/posts/uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
            <?php else: ?>
                <div style="width:100%;height:300px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;border-radius:12px;">
                    <span style="color:#ccc;">No image</span>
                </div>
            <?php endif; ?>
            <div>
                <h2><?= htmlspecialchars($row['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            </div>
        </div>
    <?php endwhile; ?>

<?php elseif($category === 'Service'): ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="service-card">
            <?php if (!empty($row['image'])): ?>
                <img src="backend/posts/uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" style="width:100%;height:200px;object-fit:cover;">
            <?php else: ?>
                <div style="width:100%;height:200px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;color:#ccc;">No image</div>
            <?php endif; ?>
            <div style="padding:20px;">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><?= htmlspecialchars($row['excerpt']) ?></p>
            </div>
        </div>
    <?php endwhile; ?>

<?php else: ?>

    <div class="grid">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <?php if (!empty($row['image'])): ?>
                    <img src="backend/posts/uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                <?php else: ?>
                    <div style="width:100%;height:180px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;border-radius:10px;margin-bottom:10px;">
                        <span style="color:#ccc;">No image</span>
                    </div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><?= htmlspecialchars($row['excerpt']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>

<?php endif; ?>

</body>
</html>