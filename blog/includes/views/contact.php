<?php
/**
 * Contact Page View
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

$pageTitle = 'Contact Us';
$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    $csrfToken = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrfToken)) {
        $errorMessage = 'Invalid security token. Please try again.';
    } elseif (empty($name) || empty($email) || empty($message)) {
        $errorMessage = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please enter a valid email address.';
    } else {
        // Here you would typically send an email or save to database
        // For now, we'll just show a success message
        $successMessage = 'Thank you for contacting us. We will get back to you soon.';
        
        // Example: mail(ADMIN_EMAIL, $subject, $message, "From: $name <$email>");
    }
}

// Generate CSRF token
$csrfToken = generateCsrfToken();
?>

<div class="container">
    <div class="page-content">
        <h1>Contact Us</h1>
        
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <?php echo e($successMessage); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-error">
                <?php echo e($errorMessage); ?>
            </div>
        <?php endif; ?>
        
        <div class="contact-form-wrapper">
            <form method="POST" action="" class="contact-form">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($name) ? e($name) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? e($email) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" value="<?php echo isset($subject) ? e($subject) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required><?php echo isset($message) ? e($message) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
        
        <div class="contact-info">
            <h2>Other Ways to Reach Us</h2>
            <p>Email: <?php echo e(SITE_EMAIL); ?></p>
        </div>
    </div>
</div>
