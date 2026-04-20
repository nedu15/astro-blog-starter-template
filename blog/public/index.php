<?php
/**
 * Blog System - Main Entry Point
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

// Check if blog is installed
if (!file_exists(dirname(__DIR__) . '/.installed')) {
    header('Location: ../installer/index.php');
    exit;
}

// Error reporting for production (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');

// Include configuration
require_once BASE_PATH . '/config.php';

// Include essential files
require_once BASE_PATH . '/includes/database.php';
require_once BASE_PATH . '/includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get requested page
$page = isset($_GET['page']) ? trim($_GET['page']) : 'home';

// Simple routing
switch ($page) {
    case 'home':
        $content_file = BASE_PATH . '/includes/views/home.php';
        break;
    case 'post':
        $content_file = BASE_PATH . '/includes/views/post.php';
        break;
    case 'category':
        $content_file = BASE_PATH . '/includes/views/category.php';
        break;
    case 'about':
        $content_file = BASE_PATH . '/includes/views/about.php';
        break;
    case 'contact':
        $content_file = BASE_PATH . '/includes/views/contact.php';
        break;
    default:
        $content_file = BASE_PATH . '/includes/views/404.php';
        break;
}

// Include header
include BASE_PATH . '/includes/header.php';

// Include content
if (file_exists($content_file)) {
    include $content_file;
} else {
    include BASE_PATH . '/includes/views/404.php';
}

// Include footer
include BASE_PATH . '/includes/footer.php';
