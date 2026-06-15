<?php
/**
 * BlogHub - PHP Blog Platform Configuration
 * SQL Server Database Connection
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Get environment variable with default fallback
 */
function env_value($key, $default)
{
    $value = getenv($key);
    return $value === false || $value === '' ? $default : $value;
}

// Database Configuration for SQL Server
define('DB_SERVER', env_value('BLOG_DB_SERVER', 'localhost'));
define('DB_PORT', env_value('BLOG_DB_PORT', '1433'));
define('DB_USER', env_value('BLOG_DB_USER', 'sa'));
define('DB_PASS', env_value('BLOG_DB_PASS', 'YourPassword123'));
define('DB_NAME', env_value('BLOG_DB_NAME', 'BlogDB'));

// Application Configuration
define('BASE_URL', env_value('BLOG_BASE_URL', 'http://localhost:8000/'));
define('ADMIN_PASS', env_value('BLOG_ADMIN_PASS', 'admin123'));
define('POSTS_PER_PAGE', max(1, (int) env_value('BLOG_POSTS_PER_PAGE', 5)));

/**
 * SQL Server Connection using PDO
 */
try {
    $connectionString = sprintf(
        'sqlsrv:Server=%s,%s;Database=%s',
        DB_SERVER,
        DB_PORT,
        DB_NAME
    );

    $conn = new PDO(
        $connectionString,
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        )
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

/**
 * Sanitize user input
 */
function sanitize($data)
{
    return trim(strip_tags((string) $data));
}

/**
 * HTML escape output
 */
function e($data)
{
    return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to URL
 */
function redirect($url)
{
    header("Location: $url");
    exit();
}

/**
 * Get published posts with pagination
 */
function get_posts($conn, $limit = 10, $offset = 0)
{
    $limit = max(1, (int) $limit);
    $offset = max(0, (int) $offset);

    $query = "
        SELECT id, title, author, content, published, created_at, updated_at
        FROM posts
        WHERE published = 1
        ORDER BY created_at DESC
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY
    ";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$offset, $limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Get posts error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get total post count
 */
function get_post_count($conn)
{
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM posts WHERE published = 1");
        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    } catch (PDOException $e) {
        error_log("Post count error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Search posts
 */
function search_posts($conn, $search, $limit = 10, $offset = 0)
{
    $limit = max(1, (int) $limit);
    $offset = max(0, (int) $offset);
    $search = '%' . $search . '%';

    $query = "
        SELECT id, title, author, content, published, created_at, updated_at
        FROM posts
        WHERE published = 1 AND (title LIKE ? OR content LIKE ?)
        ORDER BY created_at DESC
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY
    ";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$search, $search, $offset, $limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Search posts error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get search count
 */
function search_count($conn, $search)
{
    $search = '%' . $search . '%';

    try {
        $stmt = $conn->prepare(
            "SELECT COUNT(*) as total FROM posts WHERE published = 1 AND (title LIKE ? OR content LIKE ?)"
        );
        $stmt->execute([$search, $search]);
        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    } catch (PDOException $e) {
        error_log("Search count error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get post by ID
 */
function get_post_by_id($conn, $id)
{
    $id = (int) $id;

    try {
        $stmt = $conn->prepare(
            "SELECT id, title, author, content, published, created_at, updated_at FROM posts WHERE id = ? AND published = 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Get post error: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all posts (admin)
 */
function get_all_posts($conn)
{
    try {
        $stmt = $conn->query("SELECT id, title, author, published, created_at FROM posts ORDER BY created_at DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Get all posts error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get post for editing
 */
function get_post_for_edit($conn, $id)
{
    $id = (int) $id;

    try {
        $stmt = $conn->prepare("SELECT id, title, author, content, published FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Get post for edit error: " . $e->getMessage());
        return null;
    }
}

/**
 * Get blog statistics
 */
function get_blog_stats($conn)
{
    try {
        $stmt = $conn->query(
            "SELECT
                (SELECT COUNT(*) FROM posts WHERE published = 1) as posts,
                (SELECT COUNT(*) FROM comments WHERE approved = 1) as comments"
        );
        $result = $stmt->fetch();
        return $result ?: ['posts' => 0, 'comments' => 0];
    } catch (PDOException $e) {
        error_log("Blog stats error: " . $e->getMessage());
        return ['posts' => 0, 'comments' => 0];
    }
}

/**
 * Get comments for post
 */
function get_comments($conn, $post_id, $limit = 10)
{
    $post_id = (int) $post_id;

    try {
        $stmt = $conn->prepare(
            "SELECT id, author, email, content, created_at
             FROM comments
             WHERE post_id = ? AND approved = 1
             ORDER BY created_at DESC
             OFFSET 0 ROWS FETCH NEXT ? ROWS ONLY"
        );
        $stmt->execute([$post_id, max(1, (int) $limit)]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Get comments error: " . $e->getMessage());
        return [];
    }
}

/**
 * Add comment
 */
function add_comment($conn, $post_id, $author, $email, $content)
{
    $post_id = (int) $post_id;

    try {
        $stmt = $conn->prepare(
            "INSERT INTO comments (post_id, author, email, content, approved, created_at)
             VALUES (?, ?, ?, ?, 0, GETDATE())"
        );
        $stmt->execute([$post_id, $author, $email, $content]);
        return true;
    } catch (PDOException $e) {
        error_log("Add comment error: " . $e->getMessage());
        return false;
    }
}
?>
