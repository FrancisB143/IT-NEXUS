<?php
    session_start();
    include 'config.php';

    $searchQuery = '';
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchQuery = $_GET['search'];
    }

    $sql = "SELECT id, title, content, image_url, topic FROM articles WHERE status = 'approved' AND (title LIKE ? OR content LIKE ? OR topic LIKE ?) ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Technologue</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="main-content">
        
        <div class="logo-header">
            <img src="logo/technologueLogo.png" alt="Logo" class="logo">
            <h1>The Technologue</h1> 
        </div>

        <h4>Bridging the Gaps Between Science and the People</h4>
        <p style="grid-column: 1 / -1; margin-top: 0;">LATEST NEWS ABOUT COMPUTERS, CYBERSECURITY AND NETWORKING</p>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-image">
                    <?php if (!empty($row['image_url'])): ?>
                        <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Article Image">
                    <?php else: ?>
                        <img src="" alt="No Image">
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <span class="card-category"><?= htmlspecialchars($row['topic']) ?></span>
                    <h3 class="card-title"><?= htmlspecialchars($row['title']) ?></h3>
                    <p class="card-excerpt"><?= htmlspecialchars(mb_strimwidth($row['content'], 0, 100, "...")) ?></p>
                    <a href="viewStu_article.php?id=<?= $row['id'] ?>" class="read-more">Read more →</a>
                </div>
            </div>
        <?php endwhile; ?>

    </div>

    <div class="sidebar">
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search articles...">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="contributor-box" onclick="window.location.href='signup.php'">
            <h3>Want to be a contributor?</h3>
            <p>Sign up now!</p>
        </div>

        <div class="admin-box" onclick="window.location.href='contributor-login.php'">
            <h3>Login</h3>
        </div>

        <h3>TRENDING TOPICS</h3>
        <ul>
            <li><a href="#">AI Regulation</a></li>
            <li><a href="#">Blockchain Security</a></li>
            <li><a href="#">Cloud Migration</a></li>
            <li><a href="#">Edge Computing</a></li>
        </ul>
    </div>

</body>
</html>
