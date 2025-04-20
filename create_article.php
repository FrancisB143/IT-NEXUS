<?php 
    include 'config.php'; 
    session_start();  

    if (!isset($_SESSION['user_id'])) {     
        header("Location: login.php");     
        exit(); 
    }  

    $userId = $_SESSION['user_id'];  

    if ($_SERVER["REQUEST_METHOD"] == "POST") {     
        $title = trim($_POST['title']);     
        $content = trim($_POST['content']);     
        $topic = $_POST['topic']; 
        $image = $_FILES['image'];      
    
        $imagePath = '';     
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

        if (!empty($title) && !empty($content) && !empty($topic)) {             
            $stmt = $conn->prepare("INSERT INTO articles (title, content, image_url, topic, contributor_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssi", $title, $content, $imagePath, $topic, $userId);
            $stmt->execute();

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
    <title>Create Article</title>
    <link rel="stylesheet" href="create_article.css">
</head>
<body>
    <div class="container">
        <h1>Create New Article</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required></textarea>
            </div>

            <div class="form-group">
                <label for="topic">Select Topic</label>
                <select id="topic" name="topic" required>
                    <option value="AI">AI</option>
                    <option value="Machine Learning">Machine Learning</option>
                    <option value="Deep Learning">Deep Learning</option>
                    <option value="Neural Networks">Neural Networks</option>
                    <option value="Blockchain">Blockchain</option>
                    <option value="Cryptocurrency">Cryptocurrency</option>
                    <option value="Cybersecurity">Cybersecurity</option>
                    <option value="Data Privacy">Data Privacy</option>
                    <option value="Cloud Computing">Cloud Computing</option>
                    <option value="Internet of Things (IoT)">Internet of Things (IoT)</option>
                    <option value="Big Data">Big Data</option>
                    <option value="Quantum Computing">Quantum Computing</option>
                    <option value="Edge Computing">Edge Computing</option>
                    <option value="Augmented Reality (AR)">Augmented Reality (AR)</option>
                    <option value="Virtual Reality (VR)">Virtual Reality (VR)</option>
                    <option value="Mixed Reality">Mixed Reality</option>
                    <option value="5G">5G</option>
                    <option value="Web3">Web3</option>
                    <option value="DevOps">DevOps</option>
                    <option value="Tech News">Tech News</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn">Submit Article</button>
        </form>

        <a class="back-link" href="contributor.php">&larr; Back to My Articles</a>
    </div>
</body>
</html>
