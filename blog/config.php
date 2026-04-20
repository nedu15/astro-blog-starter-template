<?php
/**
 * Database Configuration
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'your_database_name');
define('DB_CHARSET', 'utf8mb4');

// Site configuration
define('SITE_TITLE', 'My Blog');
define('SITE_DESCRIPTION', 'A PHP Blog System');
define('SITE_EMAIL', 'admin@example.com');
define('ADMIN_EMAIL', 'admin@example.com');

// Upload configuration
define('UPLOAD_DIR', BASE_PATH . '/uploads/');
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Pagination
define('POSTS_PER_PAGE', 10);

// Security
define('HASH_COST', 10);
define('SESSION_LIFETIME', 3600);

// Timezone
date_default_timezone_set('UTC');
