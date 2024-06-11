<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
require 'db.php';

$editMode = false;
$announcement = ['id' => '', 'title' => '', 'body' => '']; // Updated 'content' to 'body'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $title = $_POST['title'];
        $body = $_POST['body']; // Updated 'content' to 'body'

        $stmt = $pdo->prepare('INSERT INTO announcements (title, body) VALUES (?, ?)');
        $stmt->execute([$title, $body]); // Updated 'content' to 'body'
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $body = $_POST['body']; // Updated 'content' to 'body'

        $stmt = $pdo->prepare('UPDATE announcements SET title = ?, body = ? WHERE id = ?');
        $stmt->execute([$title, $body, $id]); // Updated 'content' to 'body'
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare('DELETE FROM announcements WHERE id = ?');
        $stmt->execute([$id]);
    }
}

if (isset($_GET['edit'])) {
    $editMode = true;
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM announcements WHERE id = ?');
    $stmt->execute([$id]);
    $announcement = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements</title>
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
                <h1>Manage Announcements</h1>
                <hr>
                <form action="announcements.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($announcement['id']); ?>">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="body">Body:</label> <!-- Updated 'content' to 'body' -->
                        <textarea class="form-control" id="body" name="body" required><?php echo isset($announcement['body']) ? htmlspecialchars($announcement['body']) : ''; ?></textarea> <!-- Updated 'content' to 'body' -->
                    </div>
                    <?php if ($editMode): ?>
                        <button type="submit" name="update" class="btn btn-primary">Update Announcement</button>
                    <?php else: ?>
                        <button type="submit" name="create" class="btn btn-primary">Create Announcement</button>
                    <?php endif; ?>
                </form>
                <h2 class="mt-5">Existing Announcements</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $announcements = $pdo->query('SELECT * FROM announcements')->fetchAll();
                    foreach ($announcements as $announcement): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                            <td>
                                <a href="announcements.php?edit=1&id=<?php echo $announcement['id']; ?>" class="btn btn-warning">Edit</a>
                                <form action="announcements.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
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
