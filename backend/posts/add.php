<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authorization/login.php");
    exit();
}

include('../config/db_connect.php');

$title = '';
$description = '';
$category_id = '';
$errors = ['image' => '', 'title' => '', 'description' => '', 'category' => ''];

if (isset($_POST['submit'])) {
    // Sanitize input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];

    // Validate
    if (empty($title)) {
        $errors['title'] = 'Title is required';
    }

    if (empty($description)) {
        $errors['description'] = 'Description is required';
    }

    if (empty($category_id) || !is_numeric($category_id)) {
        $errors['category'] = 'Please select a category';
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $errors['image'] = 'Please upload an image';
    }

    if (!array_filter($errors)) {
        $upload_dir = __DIR__ . '/uploads/';
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); 
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Save to DB
            $title = mysqli_real_escape_string($conn, $title);
            $description = mysqli_real_escape_string($conn, $description);
            $category_id = mysqli_real_escape_string($conn, $category_id);
            $image_name = mysqli_real_escape_string($conn, $image_name); 

            $excerpt = mb_strimwidth($description, 0, 25, '...');

            $sql = "INSERT INTO posts(title, image, description, excerpt, category_id)
                    VALUES ('$title', '$image_name', '$description', '$excerpt', '$category_id')";

            if (mysqli_query($conn, $sql)) {
                header('Location: index.php');
                exit;
            } else {
                echo ' Query error: ' . mysqli_error($conn);
            }
        } else {
            $errors['image'] = 'Failed to upload image.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <link rel="stylesheet" href="../css/admin-shared.css">
</head>

<body>

<?php $nav_prefix = '../'; include '../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="page-header">
    <div>
      <h1>Add New Post</h1>
    </div>
  </div>

  <div class="content">
    <div class="form-card">
      <h2>Add New Post</h2>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
            
        <div class="field">
          <label>Title:</label>
          <input type="text" name="title" value="<?php echo $title; ?>">
          <div class="field-msg"><?php echo $errors['title'] ?? ''; ?></div>
        </div>

        <div class="field">
          <label>Image:</label>
          <input type="file" name="image">
          <div class="field-msg"><?php echo $errors['image'] ?? ''; ?></div>
        </div>

        <div class="field">
          <label>Description:</label>
          <textarea name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
          <div class="field-msg"><?php echo $errors['description'] ?? ''; ?></div>
        </div>

        <div class="field">
          <label>Category:</label>
          <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php
            $category_sql = "SELECT * FROM post_categories";
            $category_result = mysqli_query($conn, $category_sql);
            while ($cat = mysqli_fetch_assoc($category_result)) {
                $selected = ($category_id == $cat['id']) ? 'selected' : '';
                echo "<option value=\"" . $cat['id'] . "\" $selected>" . htmlspecialchars($cat['name']) . "</option>";
            }
            ?>
          </select>
          <div class="field-msg"><?php echo $errors['category'] ?? ''; ?></div>
        </div>

        <div class="form-actions">
          <input type="submit" name="submit" value="Submit" class="btn-primary">
          <a href="index.php" class="btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>

</html>