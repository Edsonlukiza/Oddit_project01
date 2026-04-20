<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../backend/authorization/login.php");
    exit();
}

include __DIR__ . '/config/db_connect.php';
$nav_prefix = '';                              
include __DIR__ . '/includes/admin-sidebar.php';

$total_users      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM users"))['n'];
$total_categories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM post_categories"))['n'];
$total_posts      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM posts"))['n'];

$admin_name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Admin';
$initials   = strtoupper(substr($admin_name, 0, 1)) . (strpos($admin_name, ' ') !== false
              ? strtoupper(substr(strstr($admin_name, ' '), 1, 1)) : '');
$today      = date('l, d F Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: #f4f5f7;
      min-height: 100vh;
      display: flex;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      width: 230px;
      flex-shrink: 0;
      background: #111318;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      position: sticky;
      top: 0;
      height: 100vh;
    }

    .sidebar-logo {
      padding: 22px 18px 18px;
      border-bottom: 0.5px solid rgba(255,255,255,.08);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-dot {
      width: 30px; height: 30px;
      border-radius: 8px;
      background: #1D9E75;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }

    .logo-dot svg {
      width: 15px; height: 15px;
      stroke: #fff; fill: none;
      stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }

    .logo-name { font-size: 14px; font-weight: 600; color: #fff; }

    .nav-section {
      padding: 18px 16px 4px;
      font-size: 10px;
      letter-spacing: 1.8px;
      text-transform: uppercase;
      color: rgba(255,255,255,.3);
    }

    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 14px; margin: 2px 8px;
      border-radius: 8px;
      font-size: 13px; color: rgba(255,255,255,.55);
      cursor: pointer; text-decoration: none;
      transition: background .15s, color .15s;
    }

    .nav-item:hover      { background: rgba(255,255,255,.07); color: #fff; }
    .nav-item.active     { background: #1D9E75; color: #fff; }

    .nav-item svg {
      width: 16px; height: 16px;
      stroke: currentColor; fill: none;
      stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
      flex-shrink: 0;
    }

    .sidebar-bottom {
      margin-top: auto;
      padding: 12px;
      border-top: 0.5px solid rgba(255,255,255,.08);
    }

    .user-row { display: flex; align-items: center; gap: 10px; padding: 8px; }

    .avatar {
      width: 34px; height: 34px;
      border-radius: 50%;
      background: #1D9E75;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 600; color: #fff;
      flex-shrink: 0;
    }

    .user-meta { flex: 1; min-width: 0; }
    .user-name { font-size: 13px; font-weight: 500; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-role { font-size: 11px; color: rgba(255,255,255,.35); }

    /* ── MAIN ── */
    .main { flex: 1; display: flex; flex-direction: column; min-width: 0; }

    .topbar {
      background: #fff;
      border-bottom: 0.5px solid #e5e7eb;
      padding: 16px 28px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 10;
    }

    .topbar h1  { font-size: 17px; font-weight: 600; color: #111; margin-bottom: 2px; }
    .topbar p   { font-size: 12px; color: #999; }

    .view-btn {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 12px; font-weight: 500;
      color: #1D9E75; border: 0.5px solid #1D9E75;
      border-radius: 6px; padding: 7px 14px;
      text-decoration: none; background: transparent;
      transition: background .15s, color .15s;
    }
    .view-btn:hover { background: #1D9E75; color: #fff; }
    .view-btn svg {
      width: 12px; height: 12px;
      stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }

    /* ── CONTENT ── */
    .content { padding: 28px; flex: 1; }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: #fff;
      border: 0.5px solid #e5e7eb;
      border-radius: 12px;
      padding: 20px;
      display: flex; flex-direction: column; gap: 14px;
    }

    .stat-top { display: flex; align-items: flex-start; justify-content: space-between; }

    .stat-icon {
      width: 40px; height: 40px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
    }

    .stat-icon svg {
      width: 18px; height: 18px;
      stroke: currentColor; fill: none;
      stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }

    .si-teal  { background: #E1F5EE; color: #0F6E56; }
    .si-blue  { background: #E6F1FB; color: #185FA5; }
    .si-amber { background: #FAEEDA; color: #854F0B; }

    .stat-badge {
      font-size: 11px; font-weight: 500;
      padding: 3px 8px; border-radius: 20px;
      background: #E1F5EE; color: #0F6E56;
    }

    .stat-num   { font-size: 30px; font-weight: 700; color: #111; line-height: 1; }
    .stat-label { font-size: 12px; color: #888; margin-top: 3px; }

    .stat-link {
      font-size: 11px; color: #1D9E75;
      text-decoration: none;
      display: inline-flex; align-items: center; gap: 3px;
    }
    .stat-link:hover { text-decoration: underline; }

    /* ── BOTTOM PANELS ── */
    .panels-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .panel {
      background: #fff;
      border: 0.5px solid #e5e7eb;
      border-radius: 12px;
      overflow: hidden;
    }

    .panel-head {
      padding: 14px 20px;
      border-bottom: 0.5px solid #e5e7eb;
    }
    .panel-head h3 { font-size: 14px; font-weight: 600; color: #111; }

    /* Quick actions */
    .ql-list { padding: 8px; }

    .ql-item {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 12px; border-radius: 8px;
      font-size: 13px; color: #333;
      text-decoration: none;
      transition: background .15s;
      cursor: pointer;
    }
    .ql-item:hover { background: #f9fafb; }
    .ql-item:hover .ql-arr { color: #1D9E75; }

    .ql-icon {
      width: 34px; height: 34px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .ql-icon svg {
      width: 15px; height: 15px;
      stroke: currentColor; fill: none;
      stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
    }

    .qi-amber { background: #FAEEDA; color: #854F0B; }
    .qi-blue  { background: #E6F1FB; color: #185FA5; }
    .qi-teal  { background: #E1F5EE; color: #0F6E56; }
    .qi-red   { background: #FCEBEB; color: #A32D2D; }

    .ql-label { flex: 1; font-size: 13px; font-weight: 500; color: #111; }
    .ql-arr   { font-size: 13px; color: #ccc; transition: color .15s; }

    /* System info */
    .sys-row {
      padding: 12px 20px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 0.5px solid #f0f0f0;
    }
    .sys-row:last-child { border-bottom: none; }
    .sys-key { font-size: 12px; color: #888; }
    .sys-val { font-size: 12px; font-weight: 500; color: #111; display: flex; align-items: center; gap: 5px; }
    .dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
    .dot-green { background: #1D9E75; }
    .dot-amber { background: #EF9F27; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      body    { flex-direction: column; }
      .sidebar {
        width: 100%; height: auto; flex-direction: row;
        flex-wrap: wrap; position: static;
        padding: 10px; gap: 4px;
      }
      .sidebar-logo  { padding: 8px 10px; border-bottom: none; flex: 1; }
      .nav-section   { display: none; }
      .nav-item      { padding: 8px 10px; margin: 0; }
      .nav-item span { display: none; }
      .sidebar-bottom{ display: none; }
      .panels-grid   { grid-template-columns: 1fr; }
      .topbar        { flex-wrap: wrap; gap: 10px; }
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-dot">
      <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <span class="logo-name">Admin Panel</span>
  </div>

  <span class="nav-section">Main</span>

  <a href="index.php" class="nav-item active">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    <span>Dashboard</span>
  </a>
  <a href="users/users.php" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    <span>Users</span>
  </a>
  <a href="postCategories/post_categories.php" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
    <span>Categories</span>
  </a>
  <a href="posts/index.php" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    <span>Posts</span>
  </a>

  <span class="nav-section">System</span>
  <a href="../authorization/login.php" class="nav-item">
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

<!-- MAIN -->
<div class="main">

  <div class="topbar">
    <div>
      <h1>Dashboard</h1>
      <p><?= $today ?></p>
    </div>
    <a href="../frontend/index.php" target="_blank" rel="noopener noreferrer" class="view-btn">
      <svg viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      View site
    </a>
  </div>

  <div class="content">

    <!-- STAT CARDS -->
    <div class="stats-grid">

      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon si-teal">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <span class="stat-badge">Active</span>
        </div>
        <div>
          <div class="stat-num"><?= $total_users ?></div>
          <div class="stat-label">Total users</div>
        </div>
        <a class="stat-link" href="users/users.php">Manage users →</a>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon si-blue">
            <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          </div>
        </div>
        <div>
          <div class="stat-num"><?= $total_categories ?></div>
          <div class="stat-label">Total categories</div>
        </div>
        <a class="stat-link" href="postCategories/post_categories.php">Manage categories →</a>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon si-amber">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          </div>
        </div>
        <div>
          <div class="stat-num"><?= $total_posts ?></div>
          <div class="stat-label">Total posts</div>
        </div>
        <a class="stat-link" href="posts/index.php">Manage posts →</a>
      </div>

    </div>

    <!-- BOTTOM PANELS -->
    <div class="panels-grid">

      <div class="panel">
        <div class="panel-head"><h3>Quick actions</h3></div>
        <div class="ql-list">
          <a class="ql-item" href="posts/add.php">
            <div class="ql-icon qi-amber">
              <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </div>
            <span class="ql-label">New post</span>
            <span class="ql-arr">→</span>
          </a>
          <a class="ql-item" href="postCategories/add.php">
            <div class="ql-icon qi-blue">
              <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
            </div>
            <span class="ql-label">New category</span>
            <span class="ql-arr">→</span>
          </a>
          <a class="ql-item" href="users/users.php">
            <div class="ql-icon qi-teal">
              <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <span class="ql-label">Manage users</span>
            <span class="ql-arr">→</span>
          </a>
          <a class="ql-item" href="../authorization/login.php">
            <div class="ql-icon qi-red">
              <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </div>
            <span class="ql-label">Logout</span>
            <span class="ql-arr">→</span>
          </a>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head"><h3>System info</h3></div>
        <div class="sys-row">
          <span class="sys-key">Status</span>
          <span class="sys-val"><span class="dot dot-green"></span> Online</span>
        </div>
        <div class="sys-row">
          <span class="sys-key">Session</span>
          <span class="sys-val"><span class="dot dot-green"></span> Active</span>
        </div>
        <div class="sys-row">
          <span class="sys-key">Logged in as</span>
          <span class="sys-val"><?= $admin_name ?></span>
        </div>
        <div class="sys-row">
          <span class="sys-key">PHP version</span>
          <span class="sys-val"><?= phpversion() ?></span>
        </div>
        <div class="sys-row">
          <span class="sys-key">Database</span>
          <span class="sys-val"><span class="dot dot-green"></span> Connected</span>
        </div>
        <div class="sys-row">
          <span class="sys-key">Frontend</span>
          <span class="sys-val">
            <a href="../frontend/index.php" target="_blank" style="color:#1D9E75;text-decoration:none;font-size:12px;">View live →</a>
          </span>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>