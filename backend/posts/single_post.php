<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB connection
include __DIR__ . '/../../backend/config/db_connect.php';
 include __DIR__ . '/../../frontend/includes/header.php';

// Check for a valid post ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='error-msg'>Invalid post ID.</p>";
   include __DIR__ . '/../../frontend/includes/footer.php';
    exit;
}

$postId = intval($_GET['id']);

// Fetch the post
$query = "SELECT posts.*, post_categories.name AS category_name
          FROM posts
          JOIN post_categories ON posts.category_id = post_categories.id
          WHERE posts.id = $postId";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<p class='error-msg'>Post not found.</p>";
    include __DIR__ . '/../../../frontend/includes/footer.php';
    exit;
}

$post = mysqli_fetch_assoc($result);
?>

<div class="container single-post">
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>

    <p class="meta">
        <strong>Category:</strong> <?php echo htmlspecialchars($post['category_name']); ?> |
        <strong>Date:</strong> <?php echo date("F j, Y", strtotime($post['created_at'])); ?>
    </p>

    <div class="image-wrapper">
        <img src="/blog/backend/posts/uploads/<?php echo !empty($post['image']) ? htmlspecialchars($post['image']) : 'default.jpg'; ?>" alt="Post Image">
    </div>

    <div class="post-content">
        <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
    </div>

    <a href="/blog/frontend/index.php" class="back-link">← Back to Home</a>
</div>

<?php include __DIR__ . '/../../frontend/includes/footer.php'; ?>
