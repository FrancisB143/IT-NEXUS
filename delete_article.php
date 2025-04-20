<?php

    include 'config.php';
    session_start();

    if (!isset($_SESSION['admin_username'])) {
        header("Location: admin-login.php");
        exit();
    }

    if (isset($_GET['id'])) {
        $articleId = intval($_GET['id']);

        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("i", $articleId);

        if ($stmt->execute()) {
            header("Location: manage_content.php?msg=deleted");
        } else {
            header("Location: manage_content.php?msg=error");
        }

        $stmt->close();
    } else {
        header("Location: manage_content.php");
    }

?>
