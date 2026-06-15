-- BlogHub - SQL Server Database Schema
-- Run this script to initialize the SQL Server database

-- Create Database
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'BlogDB')
BEGIN
    CREATE DATABASE BlogDB;
END;
GO

USE BlogDB;
GO

-- Drop existing tables (for fresh install)
IF OBJECT_ID('dbo.comments', 'U') IS NOT NULL
    DROP TABLE dbo.comments;
GO

IF OBJECT_ID('dbo.posts', 'U') IS NOT NULL
    DROP TABLE dbo.posts;
GO

-- Create Posts Table
CREATE TABLE dbo.posts (
    id INT IDENTITY(1,1) PRIMARY KEY,
    title NVARCHAR(255) NOT NULL,
    author NVARCHAR(100) NOT NULL,
    content NVARCHAR(MAX) NOT NULL,
    published BIT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);

-- Create index for common queries
CREATE INDEX idx_posts_published ON dbo.posts (published, created_at DESC);
CREATE INDEX idx_posts_created ON dbo.posts (created_at DESC);
GO

-- Create Comments Table
CREATE TABLE dbo.comments (
    id INT IDENTITY(1,1) PRIMARY KEY,
    post_id INT NOT NULL,
    author NVARCHAR(100) NOT NULL,
    email NVARCHAR(100),
    content NVARCHAR(MAX) NOT NULL,
    approved BIT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT GETDATE(),
    CONSTRAINT fk_comments_posts FOREIGN KEY (post_id) REFERENCES dbo.posts(id) ON DELETE CASCADE
);

-- Create indexes for comments
CREATE INDEX idx_comments_post ON dbo.comments (post_id, approved);
CREATE INDEX idx_comments_approved ON dbo.comments (approved, created_at DESC);
GO

-- Insert Sample Data
BEGIN TRANSACTION;

INSERT INTO dbo.posts (title, author, content, published, created_at)
VALUES
    (
        'Welcome to BlogHub',
        'Admin',
        '<p>Welcome to BlogHub, a modern blog platform built with PHP and SQL Server. This is the perfect place to share your thoughts, ideas, and experiences with the world.</p><p>BlogHub offers a clean and intuitive interface for both readers and administrators. Create, edit, and manage posts effortlessly with our admin panel.</p>',
        1,
        DATEADD(DAY, -7, GETDATE())
    ),
    (
        'Getting Started with SQL Server',
        'Admin',
        '<p>SQL Server is a powerful relational database management system developed by Microsoft. It provides excellent performance, security, and scalability for modern applications.</p><p>In this post, we''ll explore the key features of SQL Server and how to leverage them in your projects.</p>',
        1,
        DATEADD(DAY, -5, GETDATE())
    ),
    (
        'Web Development Best Practices',
        'Admin',
        '<p>Building robust and maintainable web applications requires following best practices. Some key principles include:</p><ul><li>Writing clean, readable code</li><li>Using version control (Git)</li><li>Implementing proper error handling</li><li>Testing your code thoroughly</li><li>Securing user data and input</li></ul>',
        1,
        DATEADD(DAY, -3, GETDATE())
    ),
    (
        'Understanding Database Normalization',
        'Admin',
        '<p>Database normalization is the process of organizing data in a database to reduce redundancy and improve data integrity. There are several normal forms (1NF, 2NF, 3NF, BCNF) that help achieve this.</p><p>Proper normalization is crucial for designing efficient and maintainable databases.</p>',
        1,
        DATEADD(DAY, -1, GETDATE())
    ),
    (
        'API Design Principles',
        'Admin',
        '<p>RESTful APIs are the backbone of modern web services. When designing APIs, consider these principles:</p><ul><li>Use proper HTTP methods (GET, POST, PUT, DELETE)</li><li>Design consistent and intuitive endpoints</li><li>Implement proper error handling and status codes</li><li>Add authentication and authorization</li><li>Document your API thoroughly</li></ul>',
        1,
        GETDATE()
    );

COMMIT;
GO

-- Display confirmation
SELECT 'Database setup complete!' as Status;
SELECT COUNT(*) as TotalPosts FROM dbo.posts;
SELECT COUNT(*) as TotalComments FROM dbo.comments;
