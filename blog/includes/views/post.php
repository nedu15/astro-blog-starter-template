<?php
/**
 * Single Post View
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

$pageTitle = 'Post';
$db = Database::getInstance();

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($postId <= 0) {
    redirect(SITE_URL);
}

// Get post details
$post = $db->fetchOne("SELECT p.*, u.username as author_name, c.name as category_name 
                       FROM posts p 
                       LEFT JOIN users u ON p.author_id = u.id 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = ? AND p.status = 'published'", [$postId]);

if (!$post) {
    http_response_code(404);
    $pageTitle = 'Post Not Found';
} else {
    $pageTitle = $post['title'];
}
?>

<div class="container">
    <?php if ($post): ?>
        <article class="single-post">
            <header class="post-header">
                <div class="post-meta">
                    <span class="post-category"><?php echo e($post['category_name'] ?? 'Uncategorized'); ?></span>
                    <span class="post-date"><?php echo formatDateTime($post['created_at']); ?></span>
                </div>
                
                <h1 class="post-title"><?php echo e($post['title']); ?></h1>
                
                <div class="post-author">
                    <span>By <?php echo e($post['author_name']); ?></span>
                </div>
            </header>
            
            <?php if (!empty($post['featured_image'])): ?>
                <div class="post-featured-image">
                    <img src="<?php echo e($post['featured_image']); ?>" alt="<?php echo e($post['title']); ?>">
                </div>
            <?php endif; ?>
            
            <div class="post-body">
                <?php echo nl2br(e($post['content'])); ?>
            </div>
            
            <?php if (!empty($post['tags'])): ?>
                <div class="post-tags">
                    <strong>Tags:</strong>
                    <span><?php echo e($post['tags']); ?></span>
                </div>
            <?php endif; ?>
            
            <div class="post-navigation">
                <a href="<?php echo SITE_URL; ?>" class="back-link">&larr; Back to Home</a>
            </div>
        </article>
    <?php else: ?>
        <div class="error-message">
            <h2>Post Not Found</h2>
            <p>The post you are looking for does not exist or has been removed.</p>
            <a href="<?php echo SITE_URL; ?>" class="btn">Go to Home</a>
        </div>
    <?php endif; ?>
</div>
