<?php
/**
 * Blog System Installer
 * 
 * Web-based installation wizard for the PHP Blog System.
 * Handles database setup, admin account creation, and configuration.
 * 
 * @package BlogSystem
 * @version 1.0.0
 */

// Prevent reinstallation
if (file_exists(dirname(__DIR__) . '/.installed')) {
    die('Blog system is already installed. Delete .installed file to reinstall.');
}

session_start();

// Initialize session variables if not set
if (!isset($_SESSION['install_step'])) {
    $_SESSION['install_step'] = 1;
}

$currentStep = $_SESSION['install_step'];
$errors = [];
$success = false;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Step 2: Database Configuration
    if (isset($_POST['step']) && $_POST['step'] == 2) {
        $dbHost = trim($_POST['db_host'] ?? 'localhost');
        $dbName = trim($_POST['db_name'] ?? '');
        $dbUser = trim($_POST['db_user'] ?? '');
        $dbPass = $_POST['db_pass'] ?? '';
        
        if (empty($dbName) || empty($dbUser)) {
            $errors[] = 'Database name and username are required.';
        } else {
            // Test connection
            try {
                $dsn = "mysql:host={$dbHost};charset=utf8mb4";
                $testConn = new PDO($dsn, $dbUser, $dbPass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                
                // Create database if it doesn't exist
                $testConn->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $testConn->exec("USE `{$dbName}`");
                
                // Store credentials in session
                $_SESSION['db_config'] = [
                    'host' => $dbHost,
                    'name' => $dbName,
                    'user' => $dbUser,
                    'pass' => $dbPass
                ];
                
                $_SESSION['install_step'] = 3;
                header('Location: index.php?step=3');
                exit;
                
            } catch (PDOException $e) {
                $errors[] = 'Database connection failed: ' . $e->getMessage();
            }
        }
    }
    
    // Step 3: Admin Account & Site Settings
    if (isset($_POST['step']) && $_POST['step'] == 3) {
        $adminUser = trim($_POST['admin_username'] ?? '');
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPass = $_POST['admin_password'] ?? '';
        $adminPassConfirm = $_POST['admin_password_confirm'] ?? '';
        $siteName = trim($_POST['site_name'] ?? 'My Blog');
        $siteUrl = trim($_POST['site_url'] ?? '');
        
        // Validation
        if (empty($adminUser) || strlen($adminUser) < 3) {
            $errors[] = 'Username must be at least 3 characters.';
        }
        if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email address is required.';
        }
        if (empty($adminPass) || strlen($adminPass) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
        if ($adminPass !== $adminPassConfirm) {
            $errors[] = 'Passwords do not match.';
        }
        if (empty($siteName)) {
            $errors[] = 'Site name is required.';
        }
        
        if (empty($siteUrl)) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $siteUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            // Remove /installer from URL
            $siteUrl = str_replace('/installer', '', $siteUrl);
        }
        
        if (empty($errors)) {
            $_SESSION['admin_config'] = [
                'username' => $adminUser,
                'email' => $adminEmail,
                'password' => password_hash($adminPass, PASSWORD_DEFAULT),
                'site_name' => $siteName,
                'site_url' => rtrim($siteUrl, '/')
            ];
            
            $_SESSION['install_step'] = 4;
            header('Location: index.php?step=4');
            exit;
        }
    }
    
    // Step 4: Final Installation
    if (isset($_POST['step']) && $_POST['step'] == 4) {
        try {
            $dbConfig = $_SESSION['db_config'] ?? [];
            $adminConfig = $_SESSION['admin_config'] ?? [];
            
            if (empty($dbConfig) || empty($adminConfig)) {
                throw new Exception('Installation session expired. Please restart.');
            }
            
            // Connect to database
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8mb4";
            $db = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            
            // Read and execute SQL file
            $sqlFile = dirname(__DIR__) . '/database.sql';
            if (!file_exists($sqlFile)) {
                throw new Exception('Database schema file not found.');
            }
            
            $sql = file_get_contents($sqlFile);
            
            // Replace database name in SQL
            $sql = str_replace('blog_db', $dbConfig['name'], $sql);
            
            // Execute SQL statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $statement) {
                if (!empty($statement) && !strpos($statement, '--')) {
                    $db->exec($statement);
                }
            }
            
            // Update admin user with new credentials
            $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = 1");
            $stmt->execute([$adminConfig['username'], $adminConfig['email'], $adminConfig['password']]);
            
            // Update site settings
            $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'site_title'");
            $stmt->execute([$adminConfig['site_name']]);
            
            $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'site_url'");
            $stmt->execute([$adminConfig['site_url']]);
            
            // Create config.php
            $configContent = "<?php\n";
            $configContent .= "/**\n";
            $configContent .= " * Blog System Configuration\n";
            $configContent .= " * Auto-generated by installer\n";
            $configContent .= " * Date: " . date('Y-m-d H:i:s') . "\n";
            $configContent .= " */\n\n";
            $configContent .= "// Prevent direct access\n";
            $configContent .= "if (!defined('APP_INIT')) {\n";
            $configContent .= "    die('Direct access not permitted');\n";
            $configContent .= "}\n\n";
            $configContent .= "// Error Reporting\n";
            $configContent .= "error_reporting(E_ALL);\n";
            $configContent .= "ini_set('display_errors', 0);\n";
            $configContent .= "ini_set('log_errors', 1);\n";
            $configContent .= "ini_set('error_log', __DIR__ . '/logs/error.log');\n\n";
            $configContent .= "// Database Configuration\n";
            $configContent .= "define('DB_HOST', '" . addslashes($dbConfig['host']) . "');\n";
            $configContent .= "define('DB_NAME', '" . addslashes($dbConfig['name']) . "');\n";
            $configContent .= "define('DB_USER', '" . addslashes($dbConfig['user']) . "');\n";
            $configContent .= "define('DB_PASS', '" . addslashes($dbConfig['pass']) . "');\n";
            $configContent .= "define('DB_CHARSET', 'utf8mb4');\n\n";
            $configContent .= "// Application Configuration\n";
            $configContent .= "define('BASE_URL', '" . addslashes($adminConfig['site_url']) . "');\n";
            $configContent .= "define('BASE_PATH', dirname(__FILE__));\n";
            $configContent .= "define('SITE_NAME', '" . addslashes($adminConfig['site_name']) . "');\n";
            $configContent .= "define('TIMEZONE', 'UTC');\n\n";
            $configContent .= "date_default_timezone_set(TIMEZONE);\n";
            
            $configPath = dirname(__DIR__) . '/config.php';
            if (file_put_contents($configPath, $configContent) === false) {
                throw new Exception('Failed to create config.php. Please check file permissions.');
            }
            
            // Create logs directory if not exists
            $logsDir = dirname(__DIR__) . '/logs';
            if (!is_dir($logsDir)) {
                mkdir($logsDir, 0755, true);
            }
            
            // Create .htaccess for logs
            $htaccessContent = "Options -Indexes\nOrder Deny,Allow\nDeny from all";
            file_put_contents($logsDir . '/.htaccess', $htaccessContent);
            
            // Create .installed file
            file_put_contents(dirname(__DIR__) . '/.installed', date('Y-m-d H:i:s'));
            
            // Clear session
            session_unset();
            session_destroy();
            
            $success = true;
            
        } catch (Exception $e) {
            $errors[] = 'Installation failed: ' . $e->getMessage();
        }
    }
}

// Get current step from URL if not posting
if (isset($_GET['step']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $step = (int)$_GET['step'];
    if ($step >= 1 && $step <= 4) {
        $_SESSION['install_step'] = $step;
        $currentStep = $step;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog System Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Blog System Installer
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Step <?php echo $currentStep; ?> of 4
            </p>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo ($currentStep * 25); ?>%"></div>
        </div>
        
        <?php if ($success): ?>
            <!-- Success Message -->
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Installation Complete!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>Your blog has been successfully installed.</p>
                        </div>
                        <div class="mt-4">
                            <a href="../admin/login.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                Go to Admin Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="rounded-md bg-red-50 p-4">
                    <?php foreach ($errors as $error): ?>
                        <p class="text-sm text-red-800 mb-2"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Step 1: Welcome -->
            <?php if ($currentStep == 1): ?>
                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <div class="space-y-4">
                        <p class="text-gray-700">
                            Welcome to the Blog System installer. This wizard will guide you through the installation process.
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Database configuration</li>
                            <li>Admin account creation</li>
                            <li>Site settings</li>
                        </ul>
                        <div class="mt-6">
                            <a href="?step=2" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Start Installation
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Step 2: Database Configuration -->
            <?php if ($currentStep == 2): ?>
                <form method="POST" class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <input type="hidden" name="step" value="2">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Database Host</label>
                            <input type="text" name="db_host" value="localhost" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Database Name</label>
                            <input type="text" name="db_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Database Username</label>
                            <input type="text" name="db_user" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Database Password</label>
                            <input type="password" name="db_pass" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Test Connection & Continue
                            </button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
            
            <!-- Step 3: Admin Account -->
            <?php if ($currentStep == 3): ?>
                <form method="POST" class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <input type="hidden" name="step" value="3">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admin Username</label>
                            <input type="text" name="admin_username" required minlength="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Admin Email</label>
                            <input type="email" name="admin_email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="admin_password" required minlength="6" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="admin_password_confirm" required minlength="6" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Site Name</label>
                            <input type="text" name="site_name" value="My Blog" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Site URL</label>
                            <input type="url" name="site_url" placeholder="Auto-detected" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Leave blank for auto-detection</p>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Continue to Installation
                            </button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
            
            <!-- Step 4: Final Installation -->
            <?php if ($currentStep == 4): ?>
                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <div class="space-y-4">
                        <p class="text-gray-700">
                            Ready to install! Click the button below to complete the installation.
                        </p>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        This action cannot be undone. Make sure your database credentials are correct.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="step" value="4">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                Install Blog System
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
