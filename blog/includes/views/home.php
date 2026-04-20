<?php
/**
 * Home Page View
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

$pageTitle = 'Home';
$db = Database::getInstance();

// Get recent posts
$posts = $db->fetchAll("SELECT p.*, u.username as author_name, c.name as category_name 
                        FROM posts p 
                        LEFT JOIN users u ON p.author_id = u.id 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.status = 'published' 
                        ORDER BY p.created_at DESC 
                        LIMIT ?", [POSTS_PER_PAGE]);
?>

<div class="container">
    <div class="page-header">
        <h2>Welcome to <?php echo e(SITE_TITLE); ?></h2>
        <p><?php echo e(SITE_DESCRIPTION); ?></p>
    </div>
    
    <div class="posts-grid">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <?php if (!empty($post['featured_image'])): ?>
                        <div class="post-thumbnail">
                            <a href="<?php echo SITE_URL; ?>?page=post&id=<?php echo $post['id']; ?>">
                                <img src="<?php echo e($post['featured_image']); ?>" alt="<?php echo e($post['title']); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-category"><?php echo e($post['category_name'] ?? 'Uncategorized'); ?></span>
                            <span class="post-date"><?php echo formatDate($post['created_at']); ?></span>
                        </div>
                        
                        <h3 class="post-title">
                            <a href="<?php echo SITE_URL; ?>?page=post&id=<?php echo $post['id']; ?>">
                                <?php echo e($post['title']); ?>
                            </a>
                        </h3>
                        
                        <p class="post-excerpt">
                            <?php echo getExcerpt($post['content']); ?>
                        </p>
                        
                        <div class="post-footer">
                            <span class="post-author">By <?php echo e($post['author_name']); ?></span>
                            <a href="<?php echo SITE_URL; ?>?page=post&id=<?php echo $post['id']; ?>" class="read-more">Read More &rarr;</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-posts">
                <p>No posts available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
