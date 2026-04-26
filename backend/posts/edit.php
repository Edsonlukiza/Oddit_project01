<?php
include __DIR__ . '/../config/db_connect.php';
$errors = array('image' => '');


$id = $_GET['id'] ?? null;


if (!$id) {
    die('Error: No post ID specified.');
}

if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$excerpt = mb_strimwidth($description, 0, 25, '...');

$sql = "UPDATE posts SET title = '$title', description = '$description', excerpt = '$excerpt' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header('Location: index.php');
        exit();
    } else {
        echo 'Error updating post: ' . mysqli_error($conn);
    }
} else {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM posts WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die('Error retrieving post: ' . mysqli_error($conn));
    }
    $post = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Post</title>
  <link rel="stylesheet" href="../css/admin-shared.css">
</head>

<body>

<?php $nav_prefix = '../'; include '../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="page-header">
    <div>
      <h1>Edit Post</h1>
    </div>
  </div>

  <div class="content">
    <div class="form-card">
      <h2>Edit Post</h2>

      <form method="POST" enctype="multipart/form-data">

        <div class="field">
          <label>Title:</label>
          <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>

        <div class="field">
          <label>Image:</label>
          <input type="file" name="image">
          <div class="field-msg"><?php echo $errors['image'] ?? ''; ?></div>
        </div>

        <div class="field">
          <label>Description:</label>
          <textarea name="description" rows="4" required><?php echo htmlspecialchars($post['description']); ?></textarea>
        </div>

        <div class="form-actions">
          <input type="submit" name="submit" value="Update Post" class="btn-primary">
          <a href="index.php" class="btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>

</html>

