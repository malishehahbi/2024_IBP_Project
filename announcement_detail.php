<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$announcement_id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM announcements WHERE id = ?');
$stmt->execute([$announcement_id]);
$announcement = $stmt->fetch();

if (!$announcement) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Details</title>
    <link rel="stylesheet" href="AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="AdminLTE/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'pages/navbar.php'; ?>
    <?php include 'pages/sidebar.php'; ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h1>Announcement Details</h1>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                    </div>
                    <div class="card-body">
                    <p><?php echo isset($announcement['body']) ? htmlspecialchars($announcement['body']) : ''; ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="index.php" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include 'pages/footer.php'; ?>
</div>
<script src="AdminLTE/plugins/jquery/jquery.min.js"></script>
<script src="AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="AdminLTE/dist/js/adminlte.min.js"></script>
</body>
</html>
