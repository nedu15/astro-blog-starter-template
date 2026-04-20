<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo e(SITE_TITLE); ?> - <?php echo isset($pageTitle) ? e($pageTitle) : 'Home'; ?></title>
    <meta name="description" content="<?php echo e(SITE_DESCRIPTION); ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/responsive.css">
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo SITE_URL; ?>assets/images/favicon.ico" type="image/x-icon">
</head>
<body class="<?php echo isset($bodyClass) ? e($bodyClass) : ''; ?>">
    
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="logo">
                <a href="<?php echo SITE_URL; ?>">
                    <h1><?php echo e(SITE_TITLE); ?></h1>
                </a>
            </div>
            
            <nav class="main-navigation">
                <ul class="nav-menu">
                    <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>?page=about">About</a></li>
                    <li><a href="<?php echo SITE_URL; ?>?page=contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="site-main">
