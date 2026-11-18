# PHP Blog System

A complete, production-ready PHP blog system with admin panel, SEO features, monetization support, and branding customization. Built with plain PHP and MySQL - no frameworks required.

## Features

- **Frontend Blog**: Homepage, single posts, categories, search, about, and contact pages
- **Admin Dashboard**: Complete content management system
- **SEO Optimized**: Meta tags, Open Graph, XML sitemap generator
- **Monetization Ready**: Ad placement sections (header, inline, footer)
- **Branding System**: Custom logo, site name, and primary colors
- **Secure**: Password hashing, CSRF protection, session management
- **Responsive Design**: Tailwind CSS for modern UI
- **Easy Installation**: Web-based installer wizard

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2+
- Apache with mod_rewrite enabled
- Write permissions for uploads and logs directories

## Directory Structure

```
blog/
├── admin/                  # Admin panel
│   ├── assets/            # Admin CSS/JS
│   ├── includes/          # Admin partials
│   ├── index.php          # Dashboard
│   ├── login.php          # Admin login
│   └── logout.php         # Logout handler
├── assets/                # Frontend assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Image assets
├── includes/             # Core includes
│   ├── views/           # Page templates
│   ├── database.php     # Database class
│   ├── functions.php    # Helper functions
│   ├── header.php       # Site header
│   └── footer.php       # Site footer
├── installer/           # Installation wizard
│   ├── index.php       # Installer script
│   └── .htaccess       # Security rules
├── logs/               # Log files (auto-created)
├── public/            # Frontend entry point
│   ├── .htaccess      # URL rewriting
│   └── index.php      # Main router
├── uploads/          # User uploads
│   └── .htaccess     # Security rules
├── config.php       # Configuration (auto-generated)
├── database.sql     # Database schema
└── README.md        # This file
```

## Installation

### Automatic Installation (Recommended)

1. Upload all files to your web server via FTP or cPanel File Manager
2. Navigate to `http://yourdomain.com/installer/` in your browser
3. Follow the installation wizard:
   - Enter database credentials (host, name, username, password)
   - Create admin account (username, email, password)
   - Configure site settings (name, URL)
4. Click "Install" to complete setup
5. Access your blog at `http://yourdomain.com/public/`
6. Login to admin panel at `http://yourdomain.com/admin/`

### Manual Installation

1. Upload all files to your web server
2. Create a MySQL database and user
3. Import `database.sql` into your database
4. Rename `config.sample.php` to `config.php` and update credentials
5. Set permissions:
   ```bash
   chmod 755 uploads/
   chmod 755 logs/
   chmod 644 config.php
   ```
6. Create `.installed` file in root directory:
   ```bash
   touch .installed
   ```
7. Access your blog at `http://yourdomain.com/public/`

## Configuration

Edit `config.php` to customize:

```php
// Database settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Site settings
define('BASE_URL', 'http://yourdomain.com');
define('SITE_NAME', 'Your Blog Name');
```

## Admin Panel Features

- **Dashboard**: Overview statistics and quick actions
- **Posts Management**: Create, edit, delete blog posts
- **Categories**: Organize content by categories
- **Media Library**: Upload and manage images
- **Settings**: Configure site options
- **Branding**: Customize logo and colors
- **Ad Management**: Insert ad codes for monetization
- **SEO Tools**: Meta tags and sitemap generation

## Default Login

After installation, use the credentials you created during setup.

**Default demo credentials** (if using sample data):
- Username: `admin`
- Password: `admin123`

⚠️ **Change these immediately after first login!**

## Security Features

- Password hashing with `password_hash()`
- CSRF token protection on forms
- Session regeneration on login
- Input sanitization and validation
- SQL injection prevention with PDO prepared statements
- XSS protection with output escaping
- Secure file upload validation
- Directory access restrictions via `.htaccess`

## SEO Features

- Clean URLs with `.htaccess` rewriting
- Dynamic meta titles and descriptions
- Open Graph tags for social sharing
- XML sitemap generator (`sitemap.xml`)
- Schema.org structured data
- Semantic HTML5 markup

## Monetization

Built-in ad placement locations:
- Header banner
- Inline content ads
- Footer banner
- Sidebar widgets

Configure ad codes via Admin Panel → Settings → Advertisements.

## Customization

### Changing Colors
Admin Panel → Branding → Primary Color

### Uploading Logo
Admin Panel → Branding → Upload Logo (supports JPG, PNG, SVG, WebP)

### Adding Custom CSS
Edit `assets/css/style.css` or add custom styles in Admin Panel

## Troubleshooting

### Installation Issues
- Ensure PHP version is 7.4+
- Check database credentials
- Verify write permissions on uploads/logs folders
- Check Apache mod_rewrite is enabled

### Blank Page
- Check `logs/error.log` for errors
- Enable error display temporarily in `config.php`:
  ```php
  ini_set('display_errors', 1);
  ```

### 404 Errors on Pages
- Ensure `.htaccess` files are uploaded
- Check Apache AllowOverride is set to All
- Verify mod_rewrite is enabled

## Support

For issues and feature requests, please contact support.

## License

Commercial license - See LICENSE file for details.

## Version

1.0.0 - Production Release

---

**Built with ❤️ using Plain PHP & MySQL**
