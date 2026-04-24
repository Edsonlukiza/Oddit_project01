<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authorization/login.php");
    exit();
}

include __DIR__ . '/../config/db_connect.php';

if (isset($_GET['delete'])) {
  $id = $_GET['id'];


  if ($id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM posts WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
      header('Location: index.php');
      exit();
    } else {
      echo 'Error deleting post: ' . mysqli_error($conn);
    }
  } else {
    echo 'Invalid ID.';
  }
}

$sql = 'SELECT id, title, description, image, created_at FROM posts ORDER BY created_at DESC';
$result = mysqli_query($conn, $sql);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Posts</title>
  <link rel="stylesheet" href="../css/admin-shared.css">
</head>

<body>

<?php $nav_prefix = '../'; include __DIR__ . '/../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="page-header">
    <div>
      <h1>All Posts</h1>
    </div>
    <a href="add.php" class="add-btn">+ Add Post</a>
  </div>

  <div class="content">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>S/N</th>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sn = 1;
          foreach ($posts as $post): ?>
            <tr>
              <td><?php echo $sn++; ?></td>
              <td><?php echo htmlspecialchars($post['title']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($post['description'])); ?></td>
              <td>
                <?php if (!empty($post['image'])): ?>
                  <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" style="max-width: 150px;">
                <?php else: ?>
                  No image
                <?php endif; ?>
              </td>
              <td>
                <?php
                $datetime = new DateTime($post['created_at']);
                echo $datetime->format('d F Y, H:i:s');
                ?>
              </td>
              <td>
                <a href="edit.php?id=<?php echo $post['id']; ?>">Edit</a> |
                <a href="index.php?delete=1&id=<?php echo $post['id']; ?>" class="delete-link"
                  onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>

</html>
