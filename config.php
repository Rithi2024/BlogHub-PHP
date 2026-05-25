<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_db');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Base URL
define('BASE_URL', 'http://localhost:8000/');
define('ADMIN_PASS', 'admin123');
define('POSTS_PER_PAGE', 5);

// Utility Functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function get_posts($conn, $limit = 10, $offset = 0) {
    $sql = "SELECT * FROM posts WHERE published = 1 ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_post_count($conn) {
    $sql = "SELECT COUNT(*) as count FROM posts WHERE published = 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function get_post_by_id($conn, $id) {
    $id = intval($id);
    $sql = "SELECT * FROM posts WHERE id = $id";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

function search_posts($conn, $keyword, $limit = 10, $offset = 0) {
    $keyword = $conn->real_escape_string($keyword);
    $sql = "SELECT * FROM posts WHERE published = 1 AND (title LIKE '%$keyword%' OR content LIKE '%$keyword%') 
            ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function search_count($conn, $keyword) {
    $keyword = $conn->real_escape_string($keyword);
    $sql = "SELECT COUNT(*) as count FROM posts WHERE published = 1 AND (title LIKE '%$keyword%' OR content LIKE '%$keyword%')";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function get_blog_stats($conn) {
    $posts = $conn->query("SELECT COUNT(*) as count FROM posts WHERE published = 1")->fetch_assoc();
    $comments = $conn->query("SELECT COUNT(*) as count FROM comments WHERE approved = 1")->fetch_assoc();
    return [
        'posts' => $posts['count'],
        'comments' => $comments['count']
    ];
}
?>

