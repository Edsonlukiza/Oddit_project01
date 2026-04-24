<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authorization/login.php");
    exit();
}

include('../config/db_connect.php');

$name = '';
$errors = ['name' => ''];

if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) {
        $errors['name'] = 'Category name is required';
    } else {
        $name = mysqli_real_escape_string($conn, $_POST['name']);

        $sql = "INSERT INTO post_categories(name) VALUES ('$name')";

        if (mysqli_query($conn, $sql)) {
            // Redirect after successful insert
            header('Location: post_categories.php');
            exit();
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Post Category</title>
  <link rel="stylesheet" href="../css/admin-shared.css">
</head>
<body>

<?php $nav_prefix = '../'; include __DIR__ . '/../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="page-header">
    <div>
      <h1>Add Post Category</h1>
    </div>
  </div>

  <div class="content">
    <div class="form-card">
      <h2>Add Post Category</h2>

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="field">
          <label>Name:</label>
          <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
          <div class="field-msg"><?php echo $errors['name']; ?></div>
        </div>

        <div class="form-actions">
          <input type="submit" name="submit" value="Submit" class="btn-primary">
          <a href="post_categories.php" class="btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
