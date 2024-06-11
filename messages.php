<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['role'] == 'admin' && isset($_POST['reply'])) {
        $id = $_POST['id'];
        $reply = $_POST['reply'];

        $stmt = $pdo->prepare('UPDATE messages SET reply = ?, read_status = TRUE WHERE id = ?');
        $stmt->execute([$reply, $id]);
    } else {
        $message = $_POST['message'];
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare('INSERT INTO messages (user_id, message) VALUES (?, ?)');
        $stmt->execute([$user_id, $message]);
    }
}

$messages = $pdo->query('SELECT messages.*, users.username FROM messages JOIN users ON messages.user_id = users.id')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
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
                <h1>Messages</h1>
                <hr>
                <?php if ($_SESSION['role'] == 'user'): ?>
                <form action="messages.php" method="post">
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
                <?php endif; ?>

                <table class="table mt-4">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Message</th>
                        <th>Reply</th>
                        <th>Read Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?php echo $message['id']; ?></td>
                            <td><?php echo $message['username']; ?></td>
                            <td><?php echo $message['message']; ?></td>
                            <td><?php echo $message['reply']; ?></td>
                            <td><?php echo $message['read_status'] ? 'Read' : 'Unread'; ?></td>
                            <td>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                <form action="messages.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $message['id']; ?>">
                                    <textarea name="reply" required><?php echo $message['reply']; ?></textarea>
                                    <button type="submit" class="btn btn-primary">Reply</button>
                                </form>
                                <?php endif; ?>
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
