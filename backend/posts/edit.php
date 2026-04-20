<?php
include __DIR__ . '/../config/db_connect.php';
include '../includes/header.php';

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
  <title>Edit Post</title>
  <link rel="stylesheet" href="../css/index.css">
</head>

<body>

  <h2 class="form-title">Edit a Post</h2>

  <div class="form-container">
    <form method="POST" enctype="multipart/form-data" class="form-box">

      <label class="form-label">Title:</label>
      <input type="text" name="title" class="form-input" value="<?php echo htmlspecialchars($post['title']); ?>" required>

      <label class="form-label">Add Image:</label>
      <input type="file" name="image" class="form-input">
      <div class="error"><?php echo $errors['image'] ?? ''; ?></div>

      <label class="form-label">Description:</label>
      <textarea name="description" rows="5" class="form-input"><?php echo htmlspecialchars($post['description']); ?></textarea>

      <div class="btn-group">
        <input type="submit" name="submit" value="Update Post" class="btn btn-submit">
        <a href="index.php" class="btn cancel-btn">Cancel</a>
      </div>

    </form>
  </div>

</body>

</html>

