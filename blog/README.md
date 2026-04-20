# PHP Blog System

A complete, production-ready PHP blog system built with plain PHP and MySQL.

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache with mod_rewrite enabled

## Installation

### 1. Upload Files

Upload all files to your web server (cPanel public_html or similar directory).

### 2. Create Database

1. Create a new MySQL database in cPanel
2. Create a database user and assign it to the database
3. Import `database.sql` into your database

### 3. Configure Settings

Edit `config.php` and update the following:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'your_database_name');
```

### 4. Set Permissions

Ensure the following directories are writable:

```bash
chmod 755 uploads/
chmod 755 logs/
```

### 5. Secure Installation

- Change the default admin password (username: `admin`, password: `admin123`)
- Remove or rename `database.sql` after installation
- Enable HTTPS in production

## Directory Structure

```
blog/
├── admin/                  # Admin panel
│   ├── assets/            # Admin assets
│   │   ├── css/
│   │   └── js/
│   ├── includes/          # Admin includes
│   ├── index.php          # Dashboard
│   ├── login.php          # Login page
│   └── logout.php         # Logout handler
├── assets/                # Frontend assets
│   ├── css/
│   ├── js/
│   └── images/
├── includes/              # Core includes
│   ├── views/            # Page views
│   ├── database.php      # Database class
│   ├── functions.php     # Helper functions
│   ├── header.php        # Site header
│   └── footer.php        # Site footer
├── logs/                  # Log files
├── uploads/               # Uploaded files
├── public/               # Public directory (document root)
│   ├── index.php         # Main entry point
│   └── .htaccess         # URL rewriting
├── config.php            # Configuration file
├── database.sql          # Database schema
└── README.md             # This file
```

## Default Login

- **Username:** admin
- **Password:** admin123

**IMPORTANT:** Change this immediately after first login!

## Features

- Clean MVC-like architecture
- PDO database layer with prepared statements
- CSRF protection
- User authentication and authorization
- Post management (create, edit, delete)
- Category management
- Comment system
- Media uploads
- SEO-friendly URLs
- Responsive design
- Admin dashboard

## Security Features

- Password hashing with bcrypt
- SQL injection prevention (prepared statements)
- XSS protection (output escaping)
- CSRF token validation
- Session security
- File upload validation
- Protected directories

## License

MIT License - Feel free to use and modify.
