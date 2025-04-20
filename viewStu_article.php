<?php
    include 'config.php';
    session_start();

    if (!isset($_GET['id'])) {
        echo "Article not found.";
        exit();
    }

    $articleId = $_GET['id'];

    $stmt = $conn->prepare("SELECT title, content, created_at, image_url, topic FROM articles WHERE id = ?");
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Article not found.";
        exit();
    }

    $article = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Article</title>
    <link rel="stylesheet" href="viewStu_article.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($article['title']) ?></h1>

        <div class="article-info">
            Published on <?= date("F j, Y, g:i a", strtotime($article['created_at'])) ?>
        </div>

        <div class="article-topic">
            Topic: <?= htmlspecialchars($article['topic']) ?>
        </div>

        <?php if (!empty($article['image_url'])): ?>
            <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="Article Image" class="article-image">
        <?php endif; ?>

        <div class="article-content">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>

        <a class="back-link" href="index.php">&larr; Back to Main Page</a>
    </div>
</body>
</html>
