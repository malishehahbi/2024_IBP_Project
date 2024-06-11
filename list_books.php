<?php
session_start();
require 'db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$books = $pdo->prepare('SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR genre LIKE ?');
$books->execute(['%' . $search . '%', '%' . $search . '%', '%' . $search . '%']);
$books = $books->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
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
                <h1>Books</h1>
                <hr>
                <form action="list_books.php" method="get">
                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <table class="table mt-4">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Published Year</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td><?php echo $book['genre']; ?></td>
                            <td><?php echo $book['published_year']; ?></td>
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
