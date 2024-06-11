<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$editMode = false;
$message = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        $editMode = true;
        $book_id = $_POST['book_id'];
        $stmt = $pdo->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();
        if ($book) {
            $title = $book['title'];
            $author = $book['author'];
            $genre = $book['genre'];
            $published_year = $book['published_year'];
        }
    } elseif (isset($_POST['save_changes'])) {
        $book_id = $_POST['book_id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $published_year = $_POST['published_year'];

        if (!empty($title) && !empty($author) && !empty($genre) && !empty($published_year)) {
            $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, genre = ?, published_year = ? WHERE id = ?');
            $stmt->execute([$title, $author, $genre, $published_year, $book_id]);
            $message = 'Book edited successfully.';
        } else {
            $message = 'Please fill in all fields.';
        }
    } elseif (isset($_POST['delete'])) {
        $book_id = $_POST['book_id'];
        $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
        $stmt->execute([$book_id]);
        $message = 'Book deleted successfully.';
    } elseif (isset($_POST['add'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $published_year = $_POST['published_year'];

        if (!empty($title) && !empty($author) && !empty($genre) && !empty($published_year)) {
            $stmt = $pdo->prepare('INSERT INTO books (title, author, genre, published_year) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $author, $genre, $published_year]);
            $message = 'New book added successfully.';
        } else {
            $message = 'Please fill in all fields.';
        }
    }
}

// Fetch all books for listing
$stmt = $pdo->query('SELECT * FROM books');
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
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
                <h1>Manage Books</h1>
                <hr>
                <?php if ($editMode): ?>
                    <h3>Edit: <?php echo htmlspecialchars($title); ?><hr></h3>
                    <form action="manage_books.php" method="post">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="genre">Genre:</label>
                            <input type="text" class="form-control" id="genre" name="genre" value="<?php echo htmlspecialchars($genre); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="published_year">Published Year:</label>
                            <input type="number" class="form-control" id="published_year" name="published_year" value="<?php echo htmlspecialchars($published_year); ?>" required>
                        </div>
                        <button type="submit" name="save_changes" class="btn btn-warning">Save Changes</button>
                    </form>
                <?php else: ?>
                    <?php if (!empty($books)): ?>
                        
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Published Year</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                                    <td><?php echo htmlspecialchars($book['published_year']); ?></td>
                                    <td>
                                        <form action="manage_books.php" method="post">
                                            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['id']); ?>">
                                            <button type="submit" name="edit" class="btn btn-warning">Edit</button>
                                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No books found.</p>
                    <?php endif; ?>
                    <h2>Add New Book</h2>
                    <form action="manage_books.php" method="post">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                        <div class="form-group">
                            <label for="genre">Genre:</label>
                            <input type="text" class="form-control" id="genre" name="genre" required>
                        </div>
                        <div class="form-group">
                            <label for="published_year">Published Year:</label>
                            <input type="number" class="form-control" id="published_year" name="published_year" required>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Add Book</button>
                    </form>
                <?php endif; ?>
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
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

