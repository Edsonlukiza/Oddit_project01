<?php
session_start();
include '../config/db_connect.php';
?>


<link rel="stylesheet" href="user/header-user.css">
<header class="user-header">
    <div class="header-left">
        <h1 class="logo">
            <div><img src="../assets/images/tsms-logo.png" class="logo-img" alt="sports Logo">Sports</div>
        </h1>
    </div>
    
    <nav class="header-nav">
        <a href="../dashboard.php">dashboard</a>
    </nav>
    <div class="header-right">
        <span class="user-name"><?php echo htmlspecialchars($_SESSION['name']); ?> (User)</span>
       
    </div>
</header>