<?php
/**
 * Category View
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

$pageTitle = 'Category';
$db = Database::getInstance();

$categorySlug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;

if (empty($categorySlug)) {
    redirect(SITE_URL);
}

// Get category details
$category = $db->fetchOne("SELECT * FROM categories WHERE slug = ?", [$categorySlug]);

if (!$category) {
    http_response_code(404);
    $pageTitle = 'Category Not Found';
} else {
    $pageTitle = $category['name'];
    
    // Get posts for this category
    $offset = ($currentPage - 1) * POSTS_PER_PAGE;
    $posts = $db->fetchAll("SELECT p.*, u.username as author_name 
                            FROM posts p 
                            LEFT JOIN users u ON p.author_id = u.id 
                            WHERE p.category_id = ? AND p.status = 'published' 
                            ORDER BY p.created_at DESC 
                            LIMIT ? OFFSET ?", [$category['id'], POSTS_PER_PAGE, $offset]);
    
    // Get total count for pagination
    $totalResult = $db->fetchOne("SELECT COUNT(*) as total FROM posts WHERE category_id = ? AND status = 'published'", [$category['id']]);
    $pagination = getPagination($currentPage, $totalResult['total'], POSTS_PER_PAGE);
}
?>

<div class="container">
    <?php if ($category): ?>
        <div class="page-header">
            <h2><?php echo e($category['name']); ?></h2>
            <?php if (!empty($category['description'])): ?>
                <p><?php echo e($category['description']); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="posts-list">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <div class="post-content">
                            <div class="post-meta">
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
                
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="pagination">
                        <?php if ($pagination['has_prev']): ?>
                            <a href="?page=category&slug=<?php echo e($categorySlug); ?>&p=<?php echo $pagination['prev_page']; ?>" class="prev">&laquo; Previous</a>
                        <?php endif; ?>
                        
                        <span class="page-info">Page <?php echo $pagination['current_page']; ?> of <?php echo $pagination['total_pages']; ?></span>
                        
                        <?php if ($pagination['has_next']): ?>
                            <a href="?page=category&slug=<?php echo e($categorySlug); ?>&p=<?php echo $pagination['next_page']; ?>" class="next">Next &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-posts">
                    <p>No posts in this category yet.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="back-link-container">
            <a href="<?php echo SITE_URL; ?>" class="back-link">&larr; Back to Home</a>
        </div>
    <?php else: ?>
        <div class="error-message">
            <h2>Category Not Found</h2>
            <p>The category you are looking for does not exist.</p>
            <a href="<?php echo SITE_URL; ?>" class="btn">Go to Home</a>
        </div>
    <?php endif; ?>
</div>
