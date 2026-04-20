<?php
include __DIR__ . '/../config/db_connect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /authorization/index.php");
    exit();
}

$errors   = [];
$email    = $password = '';
$id       = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "Invalid user ID."; exit;
}

$id     = mysqli_real_escape_string($conn, $id);
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user   = mysqli_fetch_assoc($result);
mysqli_free_result($result);

if (!$user) { echo "User not found."; exit; }

$email = $user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email is required.';
    }

    if (empty($errors)) {
        $e   = mysqli_real_escape_string($conn, $email);
        $sql = empty($password)
            ? "UPDATE users SET email='$e' WHERE id=$id"
            : "UPDATE users SET email='$e', password='" . password_hash($password, PASSWORD_DEFAULT) . "' WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            header("Location: users.php");
            exit();
        } else {
            $errors['db'] = mysqli_error($conn);
        }
    }
}

mysqli_close($conn);

$admin_name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User — Admin</title>
  <style>
    <?php include __DIR__ . '/../css/admin-shared.css'; ?>
  </style>
</head>
<body>

<?php include __DIR__ . '/../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="topbar">
    <div>
      <h1>Edit user</h1>
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
        <h2>Edit user #<?= (int)$id ?></h2>
        <p>Leave password blank to keep it unchanged</p>
      </div>

      <?php if (isset($errors['db'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errors['db']) ?></div>
      <?php endif; ?>

      <form action="" method="POST">

        <div class="field <?= isset($errors['email']) ? 'field-error' : '' ?>">
          <label>Email address</label>
          <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" autocomplete="off">
          <?php if (isset($errors['email'])): ?>
            <span class="field-msg"><?= $errors['email'] ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label>New password <span class="label-hint">(optional)</span></label>
          <input type="password" name="password" placeholder="Leave blank to keep current">
        </div>

        <div class="form-actions">
          <button type="submit" name="submit" class="btn-primary">Save changes</button>
          <a href="users.php" class="btn-ghost">Cancel</a>
        </div>

      </form>
    </div>
  </div>
</div>

</body>
</html>