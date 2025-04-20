<?php
    include 'config.php';
    session_start();

    if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
        header("Location: login.php");
        exit();
    }

    $articleId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ? AND contributor_id = ?");
    $stmt->bind_param("ii", $articleId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Article not found or access denied.";
        exit();
    }

    $article = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $image = $_FILES['image'];

        $imagePath = $article['image_url'];
        if (!empty($image['name'])) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($image['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                    $imagePath = $targetFile;
                } else {
                    $error = "Sorry, there was an error uploading your image.";
                }
            } else {
                $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        }

        if (!empty($title) && !empty($content)) {
            $update = $conn->prepare("UPDATE articles SET title = ?, content = ?, image_url = ? WHERE id = ?");
            $update->bind_param("sssi", $title, $content, $imagePath, $articleId);
            $update->execute();

            header("Location: contributor.php");
            exit();
        } else {
            $error = "Please fill in all fields.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <link rel="stylesheet" href="edit_article.css">
</head>
<body>
    <div class="container">
        <h1>Edit Article</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required><?= htmlspecialchars($article['content']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image (Optional)</label>
                <?php if (!empty($article['image_url'])): ?>
                    <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="Current Image" width="100"><br>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn">Update Article</button>
        </form>

        <a href="contributor.php" class="back-link">&larr; Back to My Articles</a>
    </div>
</body>
</html>
