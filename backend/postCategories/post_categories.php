<?php

include __DIR__ . '/../css/admin-shared.css';
include __DIR__ . '/../includes/admin-sidebar.php'; 
include __DIR__ . '/../config/db_connect.php';
$nav_prefix = '../';                           
include __DIR__ . '/../includes/admin-sidebar.php';
if (isset($_GET['delete'])) {
  $id = $_GET['id'];


  if ($id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM post_categories WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
      header('Location: post_categories.php');
      exit();
    } else {
      echo 'Error deleting post: ' . mysqli_error($conn);
    }
  } else {
    echo 'Invalid ID.';
  }
}


$sql = 'SELECT id, name, created_at, updated_at FROM post_categories ORDER BY created_at DESC';
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title class="heading">Post Categories</title>
  <link rel="stylesheet" href="../css/index.css">
</head>
<body>
  <div style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Post Categories</h1>
    <a href="add.php" class="add-btn" >+ Add Category</a>
  </div>

  <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
      <tr>
        <th>S/N</th>
        <th>Name</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $sn = 1; ?>
      <?php foreach ($categories as $cat): ?>
        <tr>
          <td><?php echo $sn++; ?></td>
          <td><?php echo htmlspecialchars($cat['name']); ?></td>
          <td>
            <?php
              $created = new DateTime($cat['created_at']);
              echo $created->format('d F Y, H:i:s');
            ?>
          </td>
          <td>
            <?php
              $updated = new DateTime($cat['updated_at']);
              echo $updated->format('d F Y, H:i:s');
            ?>
          </td>
          <td>
            <a href="edit.php?id=<?php echo $cat['id']; ?>" style="margin-right: 10px;">Edit</a>
            <a href="post_categories.php?delete=1&id=<?php echo $cat['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');" style="color: red;">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- <?php include __DIR__ . '/../templates/includes/footer.php'; ?> -->
</body>
</html>
