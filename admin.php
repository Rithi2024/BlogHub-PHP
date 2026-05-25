<?php
include 'config.php';

// Check admin password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    if ($_POST['password'] === ADMIN_PASS) {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
    }
}

if (!isset($_SESSION)) {
    session_start();
}

// Check if admin is logged in
$is_admin = isset($_SESSION['admin']);

// Handle post creation
if ($is_admin && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_post'])) {
    $title = sanitize($_POST['title']);
    $author = sanitize($_POST['author']);
    $content = sanitize($_POST['content']);
    
    $sql = "INSERT INTO posts (title, author, content, published, created_at) 
            VALUES ('$title', '$author', '$content', 1, NOW())";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Post created successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fetch all posts
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
$posts = $result->fetch_all(MYSQLI_ASSOC);
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
            <h1>📚 Blog Admin</h1>
            <nav>
                <a href="index.php">Home</a>
                <?php if ($is_admin): ?>
                    <a href="?logout">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if (!$is_admin): ?>
                <div class="login-box">
                    <h2>Admin Login</h2>
                    <form method="POST">
                        <input type="password" name="password" placeholder="Enter admin password" required>
                        <button type="submit" name="login">Login</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="admin-panel">
                    <h2>Admin Dashboard</h2>
                    
                    <?php if (isset($success)): ?>
                        <div class="alert success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="alert error"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <h3>Create New Post</h3>
                    <form method="POST" class="post-form">
                        <input type="text" name="title" placeholder="Post Title" required>
                        <input type="text" name="author" placeholder="Author Name" required>
                        <textarea name="content" placeholder="Post Content" rows="10" required></textarea>
                        <button type="submit" name="create_post">Publish Post</button>
                    </form>

                    <h3>All Posts</h3>
                    <table class="posts-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                                    <td><?php echo htmlspecialchars($post['author']); ?></td>
                                    <td><?php echo date('F d, Y', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $post['id']; ?>">Edit</a>
                                        <a href="delete.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?')">Delete</a>
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
