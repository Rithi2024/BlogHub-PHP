-- Create Blog Database
CREATE DATABASE IF NOT EXISTS blog_db;
USE blog_db;

-- Create Posts Table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    content LONGTEXT NOT NULL,
    published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (created_at),
    INDEX (published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Comments Table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    author VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    content TEXT NOT NULL,
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    INDEX (post_id),
    INDEX (approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Sample Posts
INSERT INTO posts (title, author, content) VALUES
('Welcome to My Blog', 'Admin', 'This is my first blog post. Welcome to my blog where I share my thoughts and experiences.'),
('Getting Started with PHP', 'Admin', 'Learn the basics of PHP web development. PHP is a popular server-side scripting language for building dynamic websites.'),
('Database Design Best Practices', 'Admin', 'In this post, we discuss best practices for designing relational databases including normalization and indexing strategies.');
