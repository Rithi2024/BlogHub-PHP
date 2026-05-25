<?php
// post.php - View individual blog post
include 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = get_post_by_id($conn, $id);

if (!$post) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - My Blog</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>📚 My Blog</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="admin.php">Admin</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <article class="single-post">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="meta">
                    By <?php echo htmlspecialchars($post['author']); ?> 
                    on <?php echo date('F d, Y', strtotime($post['created_at'])); ?>
                </p>
                <div class="content">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
            </article>
            
            <div class="navigation">
                <a href="index.php" class="back-button">← Back to Posts</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 My Blog. All rights reserved.</p>
    </footer>
</body>
</html>
