<?php
$admin_name  = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Admin';
$initials    = strtoupper(substr($admin_name, 0, 1)) . (strpos($admin_name, ' ') !== false
              ? strtoupper(substr(strstr($admin_name, ' '), 1, 1)) : '');
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
$current_file = basename($_SERVER['PHP_SELF']);
$p = $nav_prefix ?? '../';

function nav_active(string $dir, string $file = ''): string {
    global $current_dir, $current_file;
    if ($file) {
        return ($current_dir === $dir && $current_file === $file) ? 'active' : '';
    }
    return $current_dir === $dir ? 'active' : '';
}

$is_root   = ($p === '');
$logout    = $is_root ? '../authorization/login.php'   : '../../authorization/login.php';
?>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-dot">
      <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <span class="logo-name">Admin Panel</span>
  </div>

  <span class="nav-section">Main</span>

  <a href="<?= $p ?>dashboard.php"
     class="nav-item <?= nav_active('backend', 'dashboard.php') ?>">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    <span>Dashboard</span>
  </a>

  <a href="<?= $p ?>users/users.php"
     class="nav-item <?= nav_active('users') ?>">
    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    <span>Users</span>
  </a>

  <a href="<?= $p ?>postCategories/post_categories.php"
     class="nav-item <?= nav_active('postCategories') ?>">
    <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
    <span>Categories</span>
  </a>

  <a href="<?= $p ?>posts/index.php"
     class="nav-item <?= nav_active('posts') ?>">
    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    <span>Posts</span>
  </a>

  <a href="<?= $p ?>stats/index.php"
     class="nav-item <?= nav_active('stats') ?>">
    <svg viewBox="0 0 24 24"><polyline points="21 8 21 21 3 21 3 8"/><line x1="7" y1="3" x2="17" y2="3"/><path d="M9 12v5M12 12v5M15 12v5"/></svg>
    <span>Hero Stats</span>
  </a>

  <span class="nav-section">System</span>

  <a href="<?= $logout ?>" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    <span>Logout</span>
  </a>

  <div class="sidebar-bottom">
    <div class="user-row">
      <div class="avatar"><?= $initials ?></div>
      <div class="user-meta">
        <div class="user-name"><?= $admin_name ?></div>
        <div class="user-role">Super admin</div>
      </div>
    </div>
  </div>
</aside>