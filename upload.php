<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
require_once 'db.php';
session_start();
$author = $_SESSION['username'];
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];

    // Specify the upload directory
    $upload_dir = "uploads/";
    $upload_path = $upload_dir . basename($file_name);

    // Ensure the 'uploads' directory exists and is writable
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Save project details to database
        $stmt = $conn->prepare("INSERT INTO projects (user_id, title, description, file_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $description, $upload_path);
    
        if ($stmt->execute()) {
            echo "Project uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to upload file.";
    }

    $conn->close();
}
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
