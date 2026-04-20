<?php
include('../config/db_connect.php');
include __DIR__ . '/../css/admin-shared.css'; 
include __DIR__ . '/../includes/admin-sidebar.php';

$id = '';
$name = '';
$errors = ['name' => ''];

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    
    $sql = "SELECT * FROM post_categories WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    if (!$category) {
        echo "Category not found.";
        exit();
    }

    $name = $category['name'];
} else {
    echo "No category ID provided.";
    exit();
}


if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) {
        $errors['name'] = 'Category name is required';
    } else {
        $name = mysqli_real_escape_string($conn, $_POST['name']);

        $sql = "UPDATE post_categories SET name = '$name', updated_at = NOW() WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            header('Location: post_categories.php');
            exit();
        } else {
            echo 'Error updating category: ' . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post Category</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<h1 class="form-heading">Edit Post Category</h1>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>" method="POST" class="form-container">
  <label for="name" class="form-label">Category Name:</label>
  <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control">
  <div class="error"><?php echo $errors['name']; ?></div>

  <div class="btn-group">
    <input type="submit" name="submit" value="Update Category" class="btn btn-submit">
    <a href="post_categories.php" class="cancel-btn btn">Cancel</a>
  </div>
</form>


</body>
</html>
