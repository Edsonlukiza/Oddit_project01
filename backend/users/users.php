<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /authorization/index.php");
    exit();
}
include __DIR__ . '/../config/db_connect.php';
$nav_prefix = '../';                           
include __DIR__ . '/../includes/admin-sidebar.php';

if (isset($_GET['delete'])) {
    $id = $_GET['id'] ?? null;
    if ($id && is_numeric($id)) {
        $id = mysqli_real_escape_string($conn, $id);
        if (mysqli_query($conn, "DELETE FROM users WHERE id = $id")) {
            header('Location: users.php');
            exit();
        } else {
            $delete_error = mysqli_error($conn);
        }
    }
}

$result = mysqli_query($conn, 'SELECT id, email FROM users ORDER BY id DESC');
$users  = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);

$admin_name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Admin';
$initials   = strtoupper(substr($admin_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users — Admin</title>
  <style>
    <?php include __DIR__ . '/../css/admin-shared.css'; ?>
  </style>
</head>
<body>

<?php include __DIR__ . '/../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="topbar">
    <div>
      <h1>Users</h1>
      <p><?= date('l, d F Y') ?></p>
    </div>
    <a href="../frontend/index.php" target="_blank" rel="noopener noreferrer" class="view-btn">
      <svg viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      View site
    </a>
  </div>

  <div class="content">

    <?php if (isset($delete_error)): ?>
      <div class="alert alert-danger">Error: <?= htmlspecialchars($delete_error) ?></div>
    <?php endif; ?>

    <div class="page-header">
      <div>
        <h2 class="page-title">All users</h2>
        <p class="page-sub"><?= count($users) ?> total</p>
      </div>
      <a href="add.php" class="add-btn">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add user
      </a>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px">#</th>
            <th style="width:60px">ID</th>
            <th>Email</th>
            <th style="width:130px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($users)): ?>
            <tr><td colspan="4" class="empty-row">No users found.</td></tr>
          <?php else: ?>
            <?php foreach ($users as $i => $user): ?>
              <tr>
                <td class="muted"><?= $i + 1 ?></td>
                <td class="muted"><?= (int)$user['id'] ?></td>
                <td>
                  <div class="user-cell">
                    <div class="avatar-sm"><?= strtoupper(substr($user['email'], 0, 1)) ?></div>
                    <?= htmlspecialchars($user['email']) ?>
                  </div>
                </td>
                <td>
                  <div class="action-btns">
                    <a href="edit.php?id=<?= (int)$user['id'] ?>" class="btn-edit">Edit</a>
                    <a href="users.php?delete=1&id=<?= (int)$user['id'] ?>"
                       class="btn-delete"
                       onclick="return confirm('Delete this user permanently?')">Delete</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

</body>
</html>