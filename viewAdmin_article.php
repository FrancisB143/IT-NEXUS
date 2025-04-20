<?php
    include 'config.php';
    session_start();

    if (!isset($_SESSION['admin_username'])) {
        header("Location: admin-login.php");
        exit();
    }

    $adminUsername = $_SESSION['admin_username'];

    if (!isset($_GET['id'])) {
        header("Location: admin.php?view=approve_articles");
        exit();
    }

    $articleId = $_GET['id'];

    $stmt = $conn->prepare("SELECT articles.id, articles.title, articles.content, articles.created_at, contributors.fullname, articles.image_url, articles.topic 
                            FROM articles 
                            JOIN contributors ON articles.contributor_id = contributors.id 
                            WHERE articles.id = ?");
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
    } else {
        echo "Article not found.";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Article</title>
    <link rel="stylesheet" href="viewAdmin_article.css">
</head>
<body>

    <div class="article-container">
        <div class="article-header">
            <h2><?= htmlspecialchars($article['title']) ?></h2>
            <small>By <?= htmlspecialchars($article['fullname']) ?> | Published on <?= date("F j, Y, g:i a", strtotime($article['created_at'])) ?></small>
        </div>

        <div class="article-topic">
            <strong>Topic:</strong> <?= htmlspecialchars($article['topic']) ?>
        </div>

        <?php if (!empty($article['image_url'])): ?>
            <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="Article Image" class="article-image">
        <?php endif; ?>

        <div class="article-content">
            <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
        </div>

        <a href="admin.php" class="back-link">&larr; Back to Pending Articles</a>
    </div>

</body>
</html>
