<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        // Check if the username already exists
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch();
        if ($existingUser) {
            $message = 'Username already exists. Please choose a different username.';
        } else {
            // Insert new user
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
            $stmt->execute([$username, $email, $password, $role]);
        }
    }
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Check if the new username already exists, excluding the current user
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND id != ?');
        $stmt->execute([$username, $id]);
        $existingUser = $stmt->fetch();
        if ($existingUser) {
            $message = 'Username already exists. Please choose a different username.';
        } else {
            // Update user
            $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?');
            $stmt->execute([$username, $email, $role, $id]);
        }
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Delete user
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$users = $pdo->prepare('SELECT * FROM users WHERE username LIKE ? OR email LIKE ?');
$users->execute(['%' . $search . '%', '%' . $search . '%']);
$users = $users->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
                <h1>Manage Users</h1>
                <hr>
                <?php if (!empty($message)): ?>
                    <div class="alert alert-danger"><?php echo $message; ?></div>
                <?php endif; ?>
                <form action="users.php" method="get">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
                <form action="users.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <button type="submit" name="create" class="btn btn-primary">Add User</button>
                    <input type="hidden" name="id" id="user_id"> <!-- Add hidden input field for user ID -->
                </form>

                <table class="table mt-4">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                            <td>
                                <form action="users.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                                <button class="btn btn-warning" onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">Edit</button>
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
<script>
    function editUser(user) {
        document.getElementById('username').value = user.username;
        document.getElementById('email').value = user.email;
        document.getElementById('role').value = user.role;
        document.getElementById('user_id').value = user.id;
        document.querySelector('form button[name=create]').textContent = 'Save Changes'; // Change button text to "Save Changes"
        document.querySelector('form button[name=create]').name = 'update'; // Ensure the form submits as an update
    }
</script>
</body>
</html>