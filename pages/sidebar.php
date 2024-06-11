<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light">Mohamadali's Panel</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Role: <?php echo $_SESSION['role']; ?></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if ($_SESSION['role'] == 'admin') : ?>
                    <li class="nav-item">
                        <a href="announcements.php" class="nav-link">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>Announcements</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Manage Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="manage_books.php" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Manage Books</p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="list_books.php" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Books</p>
                    </a>
                <li class="nav-item">
                        <a href="messages.php" class="nav-link">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>Messages</p>
                        </a>
                </li>
                <?php if ($_SESSION['role'] != 'admin') : ?>
                    </li>
                    <li class="nav-item">
                        <a href="change_password.php" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>
