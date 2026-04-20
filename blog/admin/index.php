<?php
/**
 * Admin Dashboard
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

// Require admin authentication
require_once dirname(__DIR__) . '/includes/functions.php';
requireAdmin();

$db = Database::getInstance();

// Get statistics
$totalPosts = $db->fetchOne("SELECT COUNT(*) as count FROM posts")['count'];
$publishedPosts = $db->fetchOne("SELECT COUNT(*) as count FROM posts WHERE status = 'published'")['count'];
$draftPosts = $db->fetchOne("SELECT COUNT(*) as count FROM posts WHERE status = 'draft'")['count'];
$totalCategories = $db->fetchOne("SELECT COUNT(*) as count FROM categories")['count'];
$totalUsers = $db->fetchOne("SELECT COUNT(*) as count FROM users")['count'];

// Get recent posts
$recentPosts = $db->fetchAll("SELECT p.*, u.username as author_name 
                              FROM posts p 
                              LEFT JOIN users u ON p.author_id = u.id 
                              ORDER BY p.created_at DESC 
                              LIMIT 5");

$pageTitle = 'Dashboard';
include 'includes/header.php';
?>

<div class="admin-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="user-info">
                <span>Welcome, <?php echo e($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon stat-posts">
                    <span><?php echo $totalPosts; ?></span>
                </div>
                <div class="stat-info">
                    <h3>Total Posts</h3>
                    <p>All posts in the system</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-published">
                    <span><?php echo $publishedPosts; ?></span>
                </div>
                <div class="stat-info">
                    <h3>Published</h3>
                    <p>Live posts</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-drafts">
                    <span><?php echo $draftPosts; ?></span>
                </div>
                <div class="stat-info">
                    <h3>Drafts</h3>
                    <p>Pending posts</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-categories">
                    <span><?php echo $totalCategories; ?></span>
                </div>
                <div class="stat-info">
                    <h3>Categories</h3>
                    <p>Post categories</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon stat-users">
                    <span><?php echo $totalUsers; ?></span>
                </div>
                <div class="stat-info">
                    <h3>Users</h3>
                    <p>Registered users</p>
                </div>
            </div>
        </div>
        
        <div class="recent-posts">
            <div class="section-header">
                <h2>Recent Posts</h2>
                <a href="posts.php" class="btn btn-primary">View All</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentPosts)): ?>
                        <?php foreach ($recentPosts as $post): ?>
                            <tr>
                                <td><?php echo $post['id']; ?></td>
                                <td><?php echo e($post['title']); ?></td>
                                <td><?php echo e($post['author_name']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo e($post['status']); ?>">
                                        <?php echo e(ucfirst($post['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($post['created_at']); ?></td>
                                <td>
                                    <a href="post-edit.php?id=<?php echo $post['id']; ?>" class="btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
