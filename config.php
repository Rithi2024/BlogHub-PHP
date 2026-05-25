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
define('BASE_URL', 'http://localhost/2-blog-platform-php-mysql/');
define('ADMIN_PASS', 'admin123'); // Change this!

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

function get_posts($conn, $limit = 10) {
    $sql = "SELECT * FROM posts WHERE published = 1 ORDER BY created_at DESC LIMIT $limit";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_post_by_id($conn, $id) {
    $id = intval($id);
    $sql = "SELECT * FROM posts WHERE id = $id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}
?>
