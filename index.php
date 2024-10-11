<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Include the database connection
require_once 'db.php';

// Fetch all projects
$stmt = $conn->prepare("SELECT id, title, description, file_path FROM projects");
$stmt->execute();
$result = $stmt->get_result();
$projects = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate My Work - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul class="nav-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="upload.php">Upload</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Rate My Work</h1>
        <h2>All Uploaded Projects</h2>
        <div class="project-list">
            <?php if (empty($projects)): ?>
                <p>No projects uploaded yet.</p>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="project-item">
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <a href="<?php echo htmlspecialchars($project['file_path']); ?>" target="_blank">View Project</a>
                        <p><a href="rate.php?project_id=<?php echo $project['id']; ?>">Rate this Project</a></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
