<?php
    include 'config.php';
    session_start();

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $error = "Please fill in all fields.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $admin = $result->fetch_assoc();

                if ($password === $admin['password']) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    header("Location: admin.php");
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Username not found.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - IT NEXUS</title>
    <link rel="stylesheet" href="admin-login.css">
</head>
<body>
<div class="login-container">
    <div class="login-header">
        <h1>IT NEXUS</h1>
        <p>Login to your admin account</p>
    </div>

    <form action="" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" 
                value="root" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="login-button">Login</button>
    </form>

    <div class="login-footer">
        <a href="contributor-login.php">Login as Contributor</a>
    </div>
</div>
</body>
</html>
