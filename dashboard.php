<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Include the database connection
require_once 'db.php';

// Fetch user's projects
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, title, description, file_path FROM projects WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css"> 
</head>
<body>
    <nav>
        <ul class="nav-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="upload.php">Upload Project</a></li>
            <li><a href="myprojects.php">My Projects</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <h2>Your Uploaded Projects</h2>
        <div class="project-list">
            <?php if (empty($projects)): ?>
                <p>You have not uploaded any projects yet.</p>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="project-item">
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <a href="<?php echo htmlspecialchars($project['file_path']); ?>" target="_blank">View Project</a>
                        <p><a href="rateing.php?project_id=<?php echo $project['id']; ?>">Rate this Project</a></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
