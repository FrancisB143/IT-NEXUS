<?php
    include 'config.php';
    session_start();

    if (!isset($_SESSION['admin_username'])) {
        header("Location: admin-login.php");
        exit();
    }

    if (!isset($_GET['id'])) {
        echo "No article ID provided.";
        exit();
    }

    $articleId = $_GET['id'];

    $stmt = $conn->prepare("UPDATE articles SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $articleId);

    if ($stmt->execute()) {
        header("Location: admin.php?message=Article approved successfully");
    } else {
        echo "Error approving article.";
    }

    $stmt->close();
    $conn->close();
    exit();
?>
