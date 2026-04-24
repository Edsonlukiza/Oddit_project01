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
    $sql = "DELETE FROM post_categories WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
      header('Location: post_categories.php');
      exit();
    } else {
      echo 'Error deleting category: ' . mysqli_error($conn);
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
  <title>Post Categories</title>
  <link rel="stylesheet" href="../css/admin-shared.css">
</head>
<body>

<?php $nav_prefix = '../'; include __DIR__ . '/../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="page-header">
    <div>
      <h1>Post Categories</h1>
    </div>
    <a href="add.php" class="add-btn">+ Add Category</a>
  </div>

  <div class="content">
    <div class="table-wrap">
      <table>
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
                <a href="edit.php?id=<?php echo $cat['id']; ?>" class="btn-edit">Edit</a>
                <a href="post_categories.php?delete=1&id=<?php echo $cat['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
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
