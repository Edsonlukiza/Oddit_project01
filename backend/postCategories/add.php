<?php
include('../config/db_connect.php');
include __DIR__ . '/../css/admin-shared.css';
include __DIR__ . '/../includes/admin-sidebar.php'; 

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
  <title>Add Post Category</title>
  <link rel="stylesheet" href="../css/index.css">
</head>
<body>

  <div class="form-container">
    <h1 class="form-title">Add Post Category</h1>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form-box">
      <label for="name">Enter name:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-input">
      <div class="error-text"><?php echo $errors['name']; ?></div>

      <input type="submit" name="submit" value="Submit" class="submit-btn">
    </form>
  </div>

</body>
</html>
