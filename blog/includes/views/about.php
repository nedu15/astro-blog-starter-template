<?php
/**
 * About Page View
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

$pageTitle = 'About Us';
?>

<div class="container">
    <div class="page-content">
        <h1>About <?php echo e(SITE_TITLE); ?></h1>
        
        <div class="about-content">
            <p>Welcome to <?php echo e(SITE_TITLE); ?>. We are dedicated to providing quality content and information to our readers.</p>
            
            <h2>Our Mission</h2>
            <p>Our mission is to deliver valuable, informative, and engaging content to our audience.</p>
            
            <h2>Contact Us</h2>
            <p>If you have any questions or suggestions, feel free to contact us at <?php echo e(SITE_EMAIL); ?>.</p>
        </div>
    </div>
</div>
