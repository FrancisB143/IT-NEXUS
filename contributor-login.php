<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM contributors WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: contributor.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Username not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT NEXUS</title>
    <link rel="stylesheet" href="contributor-login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>IT NEXUS</h1>
            <p>Login to your contributor account</p>
        </div>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required 
                       value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-button" name="login-submit">Login</button>
        </form>

        <div class="login-footer">
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            <a href="admin-login.php" class="admin-btn">Admin</a>
        </div>
    </div>
</body>
</html>