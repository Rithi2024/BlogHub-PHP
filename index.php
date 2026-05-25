<?php
include 'config.php';
$posts = get_posts($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
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
            <h2>Latest Posts</h2>
            
            <?php if ($posts): ?>
                <div class="posts-grid">
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card">
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="meta">
                                By <?php echo htmlspecialchars($post['author']); ?> 
                                on <?php echo date('F d, Y', strtotime($post['created_at'])); ?>
                            </p>
                            <p class="excerpt"><?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?></p>
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More →</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No posts available.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 My Blog. All rights reserved.</p>
    </footer>
</body>
</html>
