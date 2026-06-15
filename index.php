<?php
include 'config.php';

$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$offset = ($page - 1) * POSTS_PER_PAGE;

if ($search !== '') {
    $posts = search_posts($conn, $search, POSTS_PER_PAGE, $offset);
    $total_posts = search_count($conn, $search);
} else {
    $posts = get_posts($conn, POSTS_PER_PAGE, $offset);
    $total_posts = get_post_count($conn);
}

$total_pages = max(1, (int) ceil($total_posts / POSTS_PER_PAGE));
$stats = get_blog_stats($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $search ? 'Search: ' . e($search) . ' - ' : ''; ?>Professional Tech Blog</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Professional Tech Blog</h1>
            <p class="tagline">Sharing insights on web development, coding best practices, and tech trends</p>

            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search posts..." value="<?php echo e($search); ?>" />
                <button type="submit">Search</button>
            </form>

            <nav>
                <a href="index.php">Home</a>
                <a href="admin.php">Admin</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="blog-stats">
                <div class="stat">
                    <strong><?php echo (int) $stats['posts']; ?></strong>
                    <span>Published Posts</span>
                </div>
                <div class="stat">
                    <strong><?php echo (int) $stats['comments']; ?></strong>
                    <span>Comments</span>
                </div>
            </div>

            <?php if ($search): ?>
                <h2>Search Results for: <em><?php echo e($search); ?></em></h2>
                <p class="result-count">Found <?php echo (int) $total_posts; ?> post(s)</p>
            <?php else: ?>
                <h2>Latest Posts</h2>
            <?php endif; ?>

            <?php if ($posts): ?>
                <div class="posts-grid">
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card">
                            <h3><?php echo e($post['title']); ?></h3>
                            <p class="meta">
                                By <strong><?php echo e($post['author']); ?></strong>
                                on <?php echo date('F d, Y', strtotime($post['created_at'])); ?>
                            </p>
                            <p class="excerpt"><?php echo e(substr(strip_tags($post['content']), 0, 150)) . '...'; ?></p>
                            <a href="post.php?id=<?php echo (int) $post['id']; ?>" class="read-more">Read More -&gt;</a>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="index.php?page=1<?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="page-link">&lt;&lt; First</a>
                            <a href="index.php?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="page-link">&lt; Previous</a>
                        <?php endif; ?>

                        <span class="page-info">Page <?php echo (int) $page; ?> of <?php echo (int) $total_pages; ?></span>

                        <?php if ($page < $total_pages): ?>
                            <a href="index.php?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="page-link">Next &gt;</a>
                            <a href="index.php?page=<?php echo $total_pages; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="page-link">Last &gt;&gt;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p class="no-posts">No posts available. Check back soon!</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Professional Tech Blog. All rights reserved. | Built with PHP and SQL Server</p>
    </footer>
</body>
</html>
