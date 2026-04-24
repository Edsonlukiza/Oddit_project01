<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authorization/login.php");
    exit();
}

include __DIR__ . '/../config/db_connect.php';

$errors   = [];
$email    = $password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email is required.';
    }
    if (empty($password) || strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        $e  = mysqli_real_escape_string($conn, $email);
        $hp = password_hash($password, PASSWORD_DEFAULT);
        if (mysqli_query($conn, "INSERT INTO users (email, password) VALUES ('$e', '$hp')")) {
            header('Location: users.php');
            exit();
        } else {
            $errors['db'] = mysqli_error($conn);
        }
    }
}

$admin_name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User — Admin</title>
  <link rel="stylesheet" href="../css/admin-shared.css">
</head>
<body>

<?php $nav_prefix = '../'; include __DIR__ . '/../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="page-header">
    <div>
      <h1>Add User</h1>
    </div>
  </div>

  <div class="content">
    <div class="form-card">
      <h2>Add User</h2>

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="field">
          <label>Email:</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="user@example.com">
          <div class="field-msg"><?php echo $errors['email'] ?? ''; ?></div>
        </div>

        <div class="field">
          <label>Password:</label>
          <input type="password" name="password" placeholder="Min. 6 characters">
          <div class="field-msg"><?php echo $errors['password'] ?? ''; ?></div>
        </div>

        <div class="form-actions">
          <input type="submit" name="submit" value="Create user" class="btn-primary">
          <a href="users.php" class="btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>