<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>MLM Dashboard</title>
    // ...existing header code...
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/header.php'; ?>
        <?php include 'includes/sidebar.php'; ?>
        
        <div id="main-content" class="content-wrapper">
            <?php include 'dashboard.php'; ?>  <!-- Load dashboard by default -->
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>
    // ...existing scripts...
    <script src="assets/js/script.js"></script>
</body>
</html>
