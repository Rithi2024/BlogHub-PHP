# BlogHub - PHP Blog Platform

**BlogHub** is a professional, modern blog platform built with PHP and SQL Server. Create, manage, and publish blog posts with an intuitive admin panel and feature-rich public interface.

## 🎯 Features

- 📝 **Full Blog Management** - Create, edit, publish, and delete posts
- 🔍 **Advanced Search** - Search posts by title and content with pagination
- 💬 **Comment System** - Readers can submit comments (admin approval required)
- 👤 **Admin Panel** - Secure admin interface with password protection
- 📊 **Statistics** - Display blog stats (post count, comment count)
- 🔒 **Security** - CSRF token protection, input sanitization, SQL Server parameterized queries
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile
- 🎨 **Professional Styling** - Clean, modern CSS design
- ⚡ **Performance** - Optimized queries with proper indexing

## 📋 Tech Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| Backend | PHP | 7.4+ |
| Database | SQL Server | 2016+ |
| ORM/Driver | PDO (sqlsrv) | Native |
| Styling | CSS3 | - |
| Frontend | HTML5 | - |

## 🚀 Quick Start

### Prerequisites

- PHP 7.4+ with PDO and `sqlsrv` extension
- SQL Server 2016 or later (or SQL Server Express)
- Windows or Linux with SQL Server support

### Installation

#### 1. Database Setup

**Option A: Using SQL Server Management Studio (SSMS)**
- Open SSMS and connect to your SQL Server
- Open a new query window
- Copy and paste contents of `database_sqlserver.sql`
- Execute the script

**Option B: Using Command Line**
```bash
sqlcmd -S localhost -U sa -P YourPassword123 -i database_sqlserver.sql
```

#### 2. Configure Connection

Create `.env` file or set environment variables:

```bash
# Database Configuration
set BLOG_DB_SERVER=localhost
set BLOG_DB_PORT=1433
set BLOG_DB_USER=sa
set BLOG_DB_PASS=YourPassword123
set BLOG_DB_NAME=BlogDB

# Application Configuration
set BLOG_BASE_URL=http://localhost:8000/
set BLOG_ADMIN_PASS=admin123
set BLOG_POSTS_PER_PAGE=5
```

#### 3. Start PHP Server

```bash
php -S localhost:8000
```

#### 4. Access the Blog

- **Blog**: [http://localhost:8000](http://localhost:8000)
- **Admin**: [http://localhost:8000/admin.php](http://localhost:8000/admin.php)
  - Password: `admin123` (change before production!)

## 📖 Usage

### Public Blog Interface

- **Browse Posts** - View all published posts with pagination
- **Search** - Use the search bar to find posts by title or content
- **Read Full Post** - Click "Read More" to view full post content
- **View Comments** - See approved comments on posts
- **Submit Comment** - Leave a comment (requires approval)

### Admin Panel

1. Click **Admin** link in header
2. Enter admin password
3. Available actions:
   - ✅ **Create Post** - Add new blog post
   - ✏️ **Edit Post** - Modify existing post
   - 🗑️ **Delete Post** - Remove post
   - 👁️ **View Posts** - List all posts with status

## 📁 Project Structure

```
BlogHub-PHP/
├── index.php              # Public blog homepage
├── post.php               # Individual post view
├── admin.php              # Admin panel
├── config.php             # Database config & functions
├── styles.css             # Stylesheet
├── database_sqlserver.sql # SQL Server schema
├── README.md              # Documentation
└── .env.example           # Environment template
```

## 🔐 Security

### Built-in Security Features

- **Prepared Statements** - All queries use parameterized statements to prevent SQL injection
- **Input Sanitization** - User input is trimmed and HTML tags stripped
- **Output Encoding** - All output is HTML-escaped to prevent XSS
- **CSRF Protection** - Sessions use CSRF tokens for state-changing operations
- **Password Hashing** - (Recommended: upgrade to bcrypt for production)

### Security Checklist

- [ ] Change default admin password before deploying
- [ ] Use HTTPS in production
- [ ] Set `display_errors` to `0` in production
- [ ] Restrict database user permissions (use separate read-only user for public)
- [ ] Keep PHP and SQL Server updated
- [ ] Use environment variables, not hardcoded credentials
- [ ] Implement rate limiting on admin login
- [ ] Regular database backups

## 🧪 Testing

### Manual Testing Steps

1. **Create a Post**
   - Login to admin
   - Fill in title, author, content
   - Click "Create Post"
   - Verify post appears on homepage

2. **Search Functionality**
   - Enter search term on homepage
   - Verify results are filtered correctly
   - Test pagination

3. **Comments**
   - Submit comment on a post
   - Verify comment appears after admin approval

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| **Cannot connect to database** | Verify SQL Server is running, check credentials in config.php |
| **PDO sqlsrv driver not found** | Install PHP sqlsrv extension: `pecl install sqlsrv` |
| **Port 8000 already in use** | Use `php -S localhost:8001` or kill the process |
| **Admin login fails** | Check `BLOG_ADMIN_PASS` environment variable |
| **Posts not showing** | Run `database_sqlserver.sql` to create tables and seed data |
| **Search not working** | Verify posts table has sample data |

## 📝 Environment Variables

```
BLOG_DB_SERVER      # SQL Server host (default: localhost)
BLOG_DB_PORT        # SQL Server port (default: 1433)
BLOG_DB_USER        # Database user (default: sa)
BLOG_DB_PASS        # Database password (default: YourPassword123)
BLOG_DB_NAME        # Database name (default: BlogDB)
BLOG_BASE_URL       # Blog base URL (default: http://localhost:8000/)
BLOG_ADMIN_PASS     # Admin panel password (default: admin123)
BLOG_POSTS_PER_PAGE # Posts per page (default: 5)
```

## 🔄 Database Schema

### posts table
- `id` - Auto-incrementing primary key
- `title` - Post title
- `author` - Author name
- `content` - Post content (HTML allowed)
- `published` - Publication status (0/1)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### comments table
- `id` - Auto-incrementing primary key
- `post_id` - Foreign key to posts
- `author` - Commenter name
- `email` - Commenter email
- `content` - Comment text
- `approved` - Approval status (0/1)
- `created_at` - Creation timestamp

## 🚀 Deployment

### Windows IIS
1. Copy files to IIS wwwroot
2. Configure SQL Server connection string
3. Set up application pool identity with database access
4. Enable directory browsing for admin.php

### Linux (Apache)
1. Copy files to `/var/www/html/bloghub`
2. Ensure write permissions for error logs
3. Configure Apache to run PHP
4. Adjust connection string for Linux SQL Server connector

### Docker (Optional)

```dockerfile
FROM php:7.4-apache
RUN pecl install sqlsrv pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv
COPY . /var/www/html/
```

## 📄 License

MIT - Feel free to use this project for personal or commercial purposes.

## 📞 Support

For issues and questions, please refer to the documentation or troubleshooting section above.

---

**Build Your Blog Today! 🚀**
```

## Project Files

```text
BlogHub-PHP/
├── admin.php
├── config.php
├── database.sql
├── index.php
├── post.php
├── styles.css
└── README.md
```

## Quality Checks

Run PHP syntax checks:

```bash
php -l config.php
php -l index.php
php -l post.php
php -l admin.php
```

## Security Notes

- Public output is escaped with `htmlspecialchars`.
- Database writes and dynamic reads use prepared statements.
- Admin create, edit, and delete actions use a CSRF token.
- Database credentials and the admin password can be provided through environment variables.
