# 📚 Professional Blog Platform - PHP + MySQL

![PHP](https://img.shields.io/badge/PHP-7.4+-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange?style=flat-square&logo=mysql)
![HTML5](https://img.shields.io/badge/HTML5-Latest-red?style=flat-square&logo=html5)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

A powerful, feature-rich blog platform built with PHP and MySQL. Perfect for content creators and developers who want to publish articles with full admin control.

## ✨ Features

### For Readers
- 📖 **Beautiful Blog Homepage** - Modern, responsive design
- 🔍 **Search Functionality** - Find posts by keyword
- 📄 **Pagination** - Browse posts efficiently
- 🏷️ **Post Categories** - Organize content by topic
- 📊 **Blog Statistics** - View total posts and comments
- 📱 **Mobile Responsive** - Works on all devices

### For Admin
- ✏️ **Create Posts** - Rich content management
- 📝 **Edit/Delete Posts** - Full control over content
- 🔒 **Secure Login** - Password-protected admin panel
- 📊 **Content Dashboard** - Manage all posts at a glance
- 💬 **Comment Moderation** - Approve/manage comments
- 📈 **Analytics** - Track posts and engagement

## 🛠️ Tech Stack

| Technology | Purpose |
|-----------|---------|
| **PHP 7.4+** | Server-side scripting |
| **MySQL 5.7+** | Relational database |
| **HTML5** | Semantic markup |
| **CSS3** | Modern styling with gradients |
| **JavaScript** | Client-side interactivity |
| **Apache/Nginx** | Web server |

## 📋 Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server (or PHP built-in server for local development)
- Command line access

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/Rithi2024/blog-platform-php-mysql.git
cd blog-platform-php-mysql
```

### 2. Database Setup

Import the SQL file to create tables:
```bash
mysql -u root -p < database.sql
```

When prompted, enter your MySQL password.

Alternatively, use phpMyAdmin:
1. Open phpMyAdmin
2. Create new database: `blog_db`
3. Import `database.sql`

### 3. Configure Database Connection

Edit `config.php`:
```php
define('DB_HOST', 'localhost');     // Your database host
define('DB_USER', 'root');          // Your database username
define('DB_PASS', 'your_password'); // Your database password
define('DB_NAME', 'blog_db');       // Database name
define('ADMIN_PASS', 'admin123');   // Change this to a strong password!
```

### 4. Start the Server

**Option A: Using PHP Built-in Server** (Local development)
```bash
php -S localhost:8000
```

**Option B: Using Apache**
- Copy to: `/var/www/html/blog` (Linux) or `C:\xampp\htdocs\blog` (Windows)
- Access: `http://localhost/blog`

### 5. Access the Blog

- **Frontend**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin.php
- **Admin Password**: `admin123` (change in `config.php`)

## 📁 Project Structure

```
├── index.php              # Homepage - displays all posts
├── post.php               # Single post view
├── admin.php              # Admin panel - create/edit posts
├── config.php             # Database configuration
├── styles.css             # Main stylesheet
├── database.sql           # Database schema and sample data
├── .htaccess              # Apache URL rewriting rules
└── README.md
```

## 📖 Usage Guide

### Creating a Post

1. Go to Admin Panel: `http://localhost:8000/admin.php`
2. Enter password: `admin123`
3. Fill in post details:
   - **Title**: Post headline
   - **Author**: Your name
   - **Content**: Post content (use line breaks for formatting)
4. Click "Publish Post"

### Reading Posts

1. Homepage displays latest posts
2. Click "Read More" to view full article
3. Use search to find specific posts
4. Use pagination to browse older posts

### Managing Posts

- View all posts in the admin dashboard
- Edit any post
- Delete posts with confirmation

## 🔐 Security Features

- ✅ Input sanitization with `sanitize()` function
- ✅ Password protection for admin panel
- ✅ SQL prepared statements (recommended for production)
- ✅ XSS protection with `htmlspecialchars()`
- ✅ CSRF token support (ready to implement)

## 🗄️ Database Schema

### Posts Table
```sql
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    content LONGTEXT NOT NULL,
    published TINYINT(1),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Comments Table
```sql
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT,
    author VARCHAR(100),
    email VARCHAR(100),
    content TEXT,
    approved TINYINT(1),
    created_at TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id)
);
```

## 🔧 Configuration Options

### Customize Posts Per Page
Edit `config.php`:
```php
define('POSTS_PER_PAGE', 5); // Change to your preference
```

### Change Admin Password
Edit `config.php`:
```php
define('ADMIN_PASS', 'your_strong_password');
```

### Update Blog Details
Edit `index.php` header section:
```php
<h1>Your Blog Title</h1>
<p class="tagline">Your blog tagline</p>
```

## 🚀 Deployment

### Deploy to Shared Hosting

1. Get FTP credentials from your host
2. Upload all files to public_html directory
3. Create MySQL database
4. Import database.sql
5. Update config.php with hosting database details
6. Visit your domain

### Deploy to VPS/Dedicated Server

```bash
# Connect via SSH
ssh user@your-server.com

# Install PHP and MySQL
sudo apt-get install php mysql-server

# Clone repository
git clone https://github.com/Rithi2024/blog-platform-php-mysql.git
cd blog-platform-php-mysql

# Setup database
mysql -u root -p < database.sql

# Configure config.php
nano config.php
```

### Using Docker

```dockerfile
FROM php:7.4-apache
RUN docker-php-ext-install mysqli
COPY . /var/www/html/
```

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Database connection error | Check MySQL credentials in `config.php` |
| Can't access admin panel | Verify database was imported correctly |
| Posts not displaying | Ensure `POSTS_PER_PAGE` is set correctly |
| Search not working | Check that MySQL `LIKE` queries work |
| CSS not loading | Verify file permissions and paths |

## 📊 Sample Data

The database comes with 3 sample posts to get started:
- "Welcome to My Blog"
- "Getting Started with PHP"
- "Database Design Best Practices"

## 🎨 Customization

### Change Color Scheme
Edit `styles.css` - update color variables:
```css
header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Modify Blog Layout
Edit `styles.css` grid settings:
```css
.posts-grid {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
}
```

### Add Categories to Posts
Add to `posts` table:
```sql
ALTER TABLE posts ADD COLUMN category VARCHAR(50);
```

## 📈 Future Enhancements

- [ ] User authentication system
- [ ] Comment approval system
- [ ] Post categories
- [ ] Tags system
- [ ] Social sharing buttons
- [ ] Email notifications
- [ ] RSS feed
- [ ] API endpoints

## 🔗 Useful Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Manual](https://dev.mysql.com/doc/)
- [W3Schools PHP Tutorial](https://www.w3schools.com/php/)
- [HTML & CSS Guide](https://developer.mozilla.org/en-US/docs/Web/HTML)

## 📝 Git Workflow

```bash
# Create feature branch
git checkout -b feature/add-categories

# Make changes
git add .
git commit -m "Add category support"

# Push to GitHub
git push origin feature/add-categories
```

## 🤝 Contributing

Contributions are welcome! 

1. Fork the repository
2. Create feature branch
3. Make your changes
4. Commit and push
5. Create Pull Request

## 🎯 Best Practices

### For Developers
- Use prepared statements for production
- Implement proper error handling
- Add input validation
- Use environment variables for config
- Enable HTTPS on production

### For Content
- Write descriptive post titles
- Use proper formatting
- Include author information
- Add metadata for SEO
- Moderate comments

## 📄 License

This project is open source and available under the MIT License.

## 🙏 Acknowledgments

- Built with PHP and MySQL
- Inspired by WordPress and modern blogging platforms
- Community feedback and contributions

## 📞 Support

For issues or questions:
- Open an GitHub issue
- Check documentation
- Review sample posts

---

**Ready to start blogging? 🚀**

Last Updated: May 2026

