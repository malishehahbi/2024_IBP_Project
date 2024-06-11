<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require 'db.php';

$announcements = $pdo->query('SELECT * FROM announcements')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
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
                <h1>Announcements</h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($announcements as $announcement): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                            <td>
                                <a href="announcement_detail.php?id=<?php echo $announcement['id']; ?>" class="btn btn-info">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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
