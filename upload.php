<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('User is not logged in. User ID is missing.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Validate and sanitize user inputs
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);

    $upload_dir = 'uploads/';
    $upload_path = $upload_dir . basename($_FILES['file']['name']);
    $file_type = pathinfo($upload_path, PATHINFO_EXTENSION);

    $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'pdf']; // Allowed file types
    $user_id = $_SESSION['user_id']; // Get user_id from session

    // Retrieve the author's name (username) from the users table
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($author);
    $stmt->fetch();
    $stmt->close();

    // Check if file type is allowed
    if (!in_array(strtolower($file_type), $allowed_types)) {
        echo "Error: Only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
        exit;
    }

    // Check if the file was uploaded correctly
    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
        // Prepare and execute the SQL statement, including the 'author' field
        $stmt = $conn->prepare("INSERT INTO projects (user_id, title, description, file_path, author) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $title, $description, $upload_path, $author);

        if ($stmt->execute()) {
            echo "Project uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "File upload failed.";
    }

    $stmt->close();
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul class="nav-menu">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="upload.php">Upload Project</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Upload Your Project</h1>

        <!-- Display success or error messages -->
        <?php if (isset($upload_success)): ?>
            <p style="color:green;"><?php echo $upload_success; ?></p>
        <?php elseif (isset($upload_error)): ?>
            <p style="color:red;"><?php echo $upload_error; ?></p>
        <?php endif; ?>

        <!-- Upload Form -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="title">Project Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="file">Upload File:</label>
            <input type="file" id="file" name="file" accept=".jpg,.png,.pdf,.docx" required>

            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
