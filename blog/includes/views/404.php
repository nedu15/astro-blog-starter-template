<?php
/**
 * 404 Error Page View
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

$pageTitle = 'Page Not Found';
http_response_code(404);
?>

<div class="container">
    <div class="error-page">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you are looking for does not exist or has been moved.</p>
        <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Go to Home</a>
    </div>
</div>
