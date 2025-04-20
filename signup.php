<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'config.php';

    $fullname = $_POST['fullname'];
    $section = $_POST['section'];
    $idnum = $_POST['idnum'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_sql = "SELECT id FROM contributors WHERE username = '$username' OR email = '$email' OR idnum = '$idnum'";
    $result = $conn->query($check_sql);
    
    if ($result->num_rows > 0) {
        die("Username, email or ID number already exists");
    }

    $sql = "INSERT INTO contributors (fullname, section, idnum, email, username, password) 
            VALUES ('$fullname', '$section', '$idnum', '$email', '$username', '$password')";

    if ($conn->query($sql)) {
        header("Location: contributor-login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Contributor | The Technologue</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>The Technologue</h1>
            <p>Join our community of tech contributors</p>
        </div>

        <div class="signup-form">
            <h2>Contributor Sign Up</h2>

            <form method="POST" action="signup.php">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required 
                           value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="section">Section</label>
                    <input type="text" id="section" name="section" required
                           value="<?= isset($_POST['section']) ? htmlspecialchars($_POST['section']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="idnum">ID Number</label>
                    <input type="text" id="idnum" name="idnum" required
                           value="<?= isset($_POST['idnum']) ? htmlspecialchars($_POST['idnum']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required
                           value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="submit-btn">Create Contributor Account</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="contributor-login.php">Log in here</a>
            </div>
        </div>
    </div>
</body>
</html>
