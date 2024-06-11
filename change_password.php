<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_password'])) {
        // Change Password Logic
        $user_id = $_SESSION['user_id'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Retrieve current password from database
        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        // Verify current password
        if (password_verify($current_password, $user['password'])) {
            // Check if new password matches confirm password
            if ($new_password === $confirm_password) {
                // Hash and update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
                $stmt->execute([$hashed_password, $user_id]);
                $message = 'Password updated successfully.';
            } else {
                $message = 'New password and confirm password do not match.';
            }
        } else {
            $message = 'Current password is incorrect.';
        }
    } elseif (isset($_POST['delete_account'])) {
        // Delete Account Logic
        $user_id = $_SESSION['user_id'];

        // Delete associated messages
        $stmt_delete_messages = $pdo->prepare('DELETE FROM messages WHERE user_id = ?');
        $stmt_delete_messages->execute([$user_id]);

        // Then delete the user's account
        $stmt_delete_user = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt_delete_user->execute([$user_id]);

        // Log out user after deleting account
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User settings</title>
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
                <h1>User settings</h1>
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <hr>
                <h2>Change Password</h2>
                <form action="change_password.php" method="post">
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
                <hr>
                <h2>Delete Account</h2>
                <form action="change_password.php" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    <button type="submit" name="delete_account" class="btn btn-danger">Delete Account</button>
                </form>
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
