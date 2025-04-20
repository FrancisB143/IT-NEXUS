<?php
    include 'config.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: contributor-login.php");
        exit();
    }

    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT fullname FROM contributors WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($fullname);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT id, title, created_at FROM articles WHERE contributor_id = ? AND status = 'approved' ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $approved_articles = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("SELECT id, title, created_at FROM articles WHERE contributor_id = ? AND status = 'pending' ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $pending_articles = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("SELECT id, title, created_at FROM articles WHERE contributor_id = ? AND status = 'rejected' ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $rejected_articles = $stmt->get_result();
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributor Dashboard</title>
    <link rel="stylesheet" href="contributor.css">
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h1>Welcome, <?= htmlspecialchars($fullname) ?>!</h1>
            <a href="create_article.php" class="btn">➕ Create Article</a>
        </div>

        <h3>Approved Articles</h3>
        <?php if ($approved_articles->num_rows > 0): ?>
            <ul class="article-list">
                <?php while ($row = $approved_articles->fetch_assoc()): ?>
                    <li class="article-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong><?= htmlspecialchars($row['title']) ?></strong><br>
                            <small>Published on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
                        </div>
                        <div>
                            <a href="view_article.php?id=<?= $row['id'] ?>" class="btn" style="margin-right: 8px;">View</a>
                            <a href="edit_article.php?id=<?= $row['id'] ?>" class="btn" style="background-color: #28a745;">Edit</a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No approved articles yet.</p>
        <?php endif; ?>

        <h3>Rejected Articles</h3>
        <?php if ($rejected_articles->num_rows > 0): ?>
            <ul class="article-list">
                <?php while ($row = $rejected_articles->fetch_assoc()): ?>
                    <li class="article-item">
                        <strong><?= htmlspecialchars($row['title']) ?></strong><br>
                        <small>Rejected on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No rejected articles.</p>
        <?php endif; ?>

        <h3>Pending Articles</h3>
        <?php if ($pending_articles->num_rows > 0): ?>
            <ul class="article-list">
                <?php while ($row = $pending_articles->fetch_assoc()): ?>
                    <li class="article-item">
                        <strong><?= htmlspecialchars($row['title']) ?></strong><br>
                        <small>Submitted on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No pending articles.</p>
        <?php endif; ?>

        <a href="index.php" class="logout">Logout</a>
    </div>
</body>
</html>
