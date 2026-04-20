<aside class="admin-sidebar">
    <div class="sidebar-header">
        <h2><?php echo e(SITE_TITLE); ?></h2>
        <p>Admin Panel</p>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                    <span class="icon">📊</span> Dashboard
                </a>
            </li>
            <li>
                <a href="posts.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'posts.php' ? 'active' : ''; ?>">
                    <span class="icon">📝</span> Posts
                </a>
            </li>
            <li>
                <a href="post-create.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'post-create.php' ? 'active' : ''; ?>">
                    <span class="icon">➕</span> New Post
                </a>
            </li>
            <li>
                <a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : ''; ?>">
                    <span class="icon">📁</span> Categories
                </a>
            </li>
            <li>
                <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : ''; ?>">
                    <span class="icon">👥</span> Users
                </a>
            </li>
            <li>
                <a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : ''; ?>">
                    <span class="icon">⚙️</span> Settings
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="../" target="_blank">View Site</a>
        <a href="logout.php">Logout</a>
    </div>
</aside>
