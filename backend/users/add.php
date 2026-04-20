<?php
include __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../css/admin-shared.css';
include __DIR__ . '/../includes/admin-sidebar.php'; 

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /authorization/index.php");
    exit();
}

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
  <style>
    <?php include __DIR__ . '/../css/admin-shared.css'; ?>
  </style>
</head>
<body>

<?php include __DIR__ . '/../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="topbar">
    <div>
      <h1>Add user</h1>
      <p><?= date('l, d F Y') ?></p>
    </div>
    <a href="../frontend/index.php" target="_blank" rel="noopener noreferrer" class="view-btn">
      <svg viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      View site
    </a>
  </div>

  <div class="content">
    <div class="form-card">

      <div class="form-card-head">
        <h2>New user</h2>
        <p>Create an admin account</p>
      </div>

      <?php if (isset($errors['db'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errors['db']) ?></div>
      <?php endif; ?>

      <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

        <div class="field <?= isset($errors['email']) ? 'field-error' : '' ?>">
          <label>Email address</label>
          <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="user@example.com" autocomplete="off">
          <?php if (isset($errors['email'])): ?>
            <span class="field-msg"><?= $errors['email'] ?></span>
          <?php endif; ?>
        </div>

        <div class="field <?= isset($errors['password']) ? 'field-error' : '' ?>">
          <label>Password</label>
          <input type="password" name="password" placeholder="Min. 6 characters">
          <?php if (isset($errors['password'])): ?>
            <span class="field-msg"><?= $errors['password'] ?></span>
          <?php endif; ?>
        </div>

        <div class="form-actions">
          <button type="submit" name="submit" class="btn-primary">Create user</button>
          <a href="users.php" class="btn-ghost">Cancel</a>
        </div>

      </form>
    </div>
  </div>
</div>

</body>
</html>