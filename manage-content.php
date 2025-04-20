<?php
    include 'config.php';
    session_start();

    if (!isset($_SESSION['admin_username'])) {
        header("Location: admin-login.php");
        exit();
    }

    $adminUsername = $_SESSION['admin_username'];

    if (isset($_GET['delete'])) {
        $articleId = $_GET['delete'];

        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        
        header("Location: manage-content.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT articles.id, articles.title, contributors.fullname, articles.created_at 
                            FROM articles 
                            JOIN contributors ON articles.contributor_id = contributors.id 
                            WHERE articles.status = 'approved'
                            ORDER BY articles.created_at DESC");
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Technologue</title>
    <link rel="stylesheet" href="manage-content.css">
</head>
<body>

    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="admin.php">Approve Articles</a>
        <a href="manage-content.php">Manage Content</a>
        <a href="index.php" class="logout">Logout</a>
    </div>

    <div class="container">
        <h1>Welcome, <span class="admin-name"><?= htmlspecialchars($adminUsername) ?></span>!</h1>

        <h3>Approved Articles</h3>
        <?php if ($result->num_rows > 0): ?>
            <ul class="article-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="article-item">
                        <div>
                            <strong><?= htmlspecialchars($row['title']) ?></strong><br>
                            <small>By <?= htmlspecialchars($row['fullname']) ?> | 
                            Published on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
                        </div>
                        <div>
                            <a href="viewAdmin_article.php?id=<?= $row['id'] ?>" class="btn">View</a>
                            <a href="manage-content.php?delete=<?= $row['id'] ?>" class="btn btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No approved articles found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
