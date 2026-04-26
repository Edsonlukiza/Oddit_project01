<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authorization/login.php");
    exit();
}

include __DIR__ . '/../config/db_connect.php';

$stats_file = __DIR__ . '/../config/stats.php';
$stats = include $stats_file;

if (isset($_POST['submit'])) {
    $new_stats = [];
    
    // Build stats array from POST data
    for ($i = 0; $i < 3; $i++) {
        if (isset($_POST['stat_number_' . $i]) && isset($_POST['stat_label_' . $i])) {
            $new_stats[] = [
                'number' => trim($_POST['stat_number_' . $i]),
                'label' => trim($_POST['stat_label_' . $i])
            ];
        }
    }
    
    // Write to stats.php file
    $content = "<?php\n";
    $content .= "// Hero Statistics Configuration\n";
    $content .= "// These can be edited via the admin panel\n\n";
    $content .= "\$stats = " . var_export($new_stats, true) . ";\n\n";
    $content .= "return \$stats;\n";
    
    if (file_put_contents($stats_file, $content)) {
        header('Location: index.php?success=1');
        exit();
    } else {
        $error = 'Failed to save stats.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hero Stats</title>
    <link rel="stylesheet" href="../css/admin-shared.css">
</head>

<body>

<?php $nav_prefix = '../'; include '../includes/admin-sidebar.php'; ?>

<div class="main">
  <div class="page-header">
    <div>
      <h1>Hero Statistics</h1>
      <p class="page-sub">Manage the statistics displayed on the home page hero section</p>
    </div>
  </div>

  <div class="content">
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success" style="background:#E1F5EE;color:#0F6E56;border:0.5px solid #5DCAA5;margin-bottom:20px;">
        ✓ Statistics updated successfully
      </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
      <div class="alert alert-danger" style="margin-bottom:20px;">
        ✗ <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <div class="form-card">
      <h2>Edit Hero Statistics</h2>
      <p style="color:#888;margin-bottom:20px;font-size:13px;">Update the 3 statistics shown on the home page hero section</p>

      <form method="POST">
        <?php foreach ($stats as $i => $stat): ?>
          <div style="background:#f9fafb;padding:20px;border-radius:8px;margin-bottom:16px;border:0.5px solid #e5e7eb;">
            <h3 style="font-size:14px;font-weight:600;margin-bottom:16px;color:#111;">Statistic <?php echo $i + 1; ?></h3>
            
            <div class="field">
              <label>Number (e.g., "120+", "8 yrs"):</label>
              <input type="text" name="stat_number_<?php echo $i; ?>" value="<?php echo htmlspecialchars($stat['number']); ?>" required>
            </div>

            <div class="field">
              <label>Label (e.g., "Projects", "Experience"):</label>
              <input type="text" name="stat_label_<?php echo $i; ?>" value="<?php echo htmlspecialchars($stat['label']); ?>" required>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="form-actions">
          <input type="submit" name="submit" value="Save Statistics" class="btn-primary">
          <a href="../dashboard.php" class="btn-ghost">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>

</html>
