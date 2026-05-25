# Blog Platform - PHP + MySQL

A simple yet powerful blog platform built with PHP and MySQL.

## Features
- 📝 Create, edit, and delete blog posts
- 👨‍💻 Admin panel for content management
- 📱 Responsive design
- 💬 Comments system
- 🔐 Password-protected admin area

## Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx server

## Installation

### 1. Database Setup
```bash
# Import the database
mysql -u root < database.sql
```

### 2. Configuration
Edit `config.php` and update:
- `DB_HOST` - Your database host (localhost)
- `DB_USER` - Your database username
- `DB_PASS` - Your database password
- `DB_NAME` - Your database name (blog_db)
- `ADMIN_PASS` - Change the admin password!

### 3. File Setup
Place all files in your web server directory (e.g., `/var/www/html/blog/`)

### 4. Access the Blog
- Frontend: `http://localhost/2-blog-platform-php-mysql/`
- Admin: `http://localhost/2-blog-platform-php-mysql/admin.php`

## File Structure
```
├── index.php         # Homepage
├── admin.php         # Admin panel
├── config.php        # Database configuration
├── styles.css        # Styling
├── database.sql      # Database setup
└── README.md
```

## Usage
1. Visit admin.php
2. Login with password (default: admin123)
3. Create new posts
4. View posts on homepage

## Security Notes
- Change `ADMIN_PASS` in config.php
- Use prepared statements for production
- Implement proper authentication system

## Tech Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Architecture**: MVC pattern

## Git Setup
```bash
git init
git add .
git commit -m "Initial blog platform"
git push origin main
```
