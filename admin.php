<?php
session_start();
include 'config.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token()
{
    return $_SESSION['csrf_token'];
}

function has_valid_csrf_token()
{
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $password = $_POST['password'] ?? '';

    if (hash_equals(ADMIN_PASS, $password)) {
        $_SESSION['admin'] = true;
        redirect('admin.php');
    }

    $error = 'Invalid admin password.';
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    redirect('admin.php');
}

$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
$success = null;
$editing_post = null;

if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['login'])) {
    if (!has_valid_csrf_token()) {
        $error = 'Your session expired. Please refresh and try again.';
    } elseif (isset($_POST['create_post'])) {
        $title = sanitize($_POST['title'] ?? '');
        $author = sanitize($_POST['author'] ?? '');
        $content = trim((string) ($_POST['content'] ?? ''));

        if ($title === '' || $author === '' || $content === '') {
            $error = 'Title, author, and content are required.';
        } else {
            $stmt = $conn->prepare(
                'INSERT INTO posts (title, author, content, published, created_at)
                 VALUES (?, ?, ?, 1, GETDATE())'
            );
            $stmt->execute([$title, $author, $content]);
            $success = 'Post created successfully.';
        }
    } elseif (isset($_POST['update_post'])) {
        $post_id = (int) ($_POST['post_id'] ?? 0);
        $title = sanitize($_POST['title'] ?? '');
        $author = sanitize($_POST['author'] ?? '');
        $content = trim((string) ($_POST['content'] ?? ''));

        if ($post_id <= 0 || $title === '' || $author === '' || $content === '') {
            $error = 'A valid post, title, author, and content are required.';
        } else {
            $stmt = $conn->prepare(
                'UPDATE posts
                 SET title = ?, author = ?, content = ?, updated_at = GETDATE()
                 WHERE id = ?'
            );
            $stmt->execute([$title, $author, $content, $post_id]);
            $success = 'Post updated successfully.';
        }
    } elseif (isset($_POST['delete_post'])) {
        $post_id = (int) ($_POST['post_id'] ?? 0);

        if ($post_id <= 0) {
            $error = 'A valid post is required for deletion.';
        } else {
            $stmt = $conn->prepare('DELETE FROM posts WHERE id = ?');
            $stmt->execute([$post_id]);
            $success = 'Post deleted successfully.';
        }
    }
}

if ($is_admin && isset($_GET['edit'])) {
    $editing_post = get_post_for_edit($conn, (int) $_GET['edit']);

    if (!$editing_post) {
        $error = 'The requested post was not found.';
    }
}

$posts = [];
if ($is_admin) {
    $posts = get_all_posts($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Blog</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Blog Admin</h1>
            <nav>
                <a href="index.php">Home</a>
                <?php if ($is_admin): ?>
                    <a href="admin.php?logout=1">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if (!$is_admin): ?>
                <div class="login-box">
                    <h2>Admin Login</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert error"><?php echo e($error); ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="password" name="password" placeholder="Enter admin password" required>
                        <button type="submit" name="login">Login</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="admin-panel">
                    <h2>Admin Dashboard</h2>

                    <?php if ($success): ?>
                        <div class="alert success"><?php echo e($success); ?></div>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="alert error"><?php echo e($error); ?></div>
                    <?php endif; ?>

                    <h3><?php echo $editing_post ? 'Edit Post' : 'Create New Post'; ?></h3>
                    <form method="POST" class="post-form">
                        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                        <?php if ($editing_post): ?>
                            <input type="hidden" name="post_id" value="<?php echo (int) $editing_post['id']; ?>">
                        <?php endif; ?>
                        <input
                            type="text"
                            name="title"
                            placeholder="Post Title"
                            value="<?php echo $editing_post ? e($editing_post['title']) : ''; ?>"
                            required
                        >
                        <input
                            type="text"
                            name="author"
                            placeholder="Author Name"
                            value="<?php echo $editing_post ? e($editing_post['author']) : ''; ?>"
                            required
                        >
                        <textarea name="content" placeholder="Post Content" rows="10" required><?php echo $editing_post ? e($editing_post['content']) : ''; ?></textarea>
                        <div class="form-actions">
                            <?php if ($editing_post): ?>
                                <button type="submit" name="update_post">Update Post</button>
                                <a href="admin.php" class="button-link">Cancel</a>
                            <?php else: ?>
                                <button type="submit" name="create_post">Publish Post</button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <h3>All Posts</h3>
                    <table class="posts-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo e($post['title']); ?></td>
                                    <td><?php echo e($post['author']); ?></td>
                                    <td><?php echo date('F d, Y', strtotime($post['created_at'])); ?></td>
                                    <td><?php echo $post['published'] ? 'Published' : 'Draft'; ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="admin.php?edit=<?php echo (int) $post['id']; ?>">Edit</a>
                                            <form method="POST" class="inline-form" onsubmit="return confirm('Delete this post?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                                                <input type="hidden" name="post_id" value="<?php echo (int) $post['id']; ?>">
                                                <button type="submit" name="delete_post" class="danger-button">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
