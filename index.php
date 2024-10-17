<?php
session_start();
require_once 'db.php';

// Fetch all projects with the author's username
$stmt = $conn->prepare("
    SELECT p.id, p.title, p.description, u.username AS author, 
           IFNULL(AVG(r.rating_value), 0) AS average_rating 
    FROM projects p
    JOIN users u ON p.user_id = u.id
    LEFT JOIN ratings r ON p.id = r.project_id
    GROUP BY p.id, u.username
");
$stmt->execute();
$projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch comments for each project
$comments = [];
foreach ($projects as $project) {
    $stmt = $conn->prepare("SELECT c.comment_text, c.created_at, u.username 
                             FROM comments c 
                             JOIN users u ON c.user_id = u.id 
                             WHERE c.project_id = ?");
    $stmt->bind_param("i", $project['id']);
    $stmt->execute();
    $comments[$project['id']] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

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
                        <p>Author: <?php echo htmlspecialchars($project['author']); ?></p> <!-- Display the author -->
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <p>Average Rating: <?php echo number_format($project['average_rating'], 2); ?>%</p>
                        <!-- The rest of your code remains unchanged -->
                 
             

                        <!-- Rating Form -->
                        <form method="POST" action="submit_rating.php">
                            <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                            <label for="rating_value">Rate this Project:</label>
                            <div class="star-rating">
                                <input type="radio" name="rating_value" value="20" required><label>&#9733;</label>
                                <input type="radio" name="rating_value" value="40"><label>&#9733;</label>
                                <input type="radio" name="rating_value" value="60"><label>&#9733;</label>
                                <input type="radio" name="rating_value" value="80"><label>&#9733;</label>
                                <input type="radio" name="rating_value" value="100"><label>&#9733;</label>
                            </div>
                            <button type="submit">Submit Rating</button>
                        </form>

                        <!-- Comments Section -->
                        <h4>Comments:</h4>
                        <?php if (!empty($comments[$project['id']])): ?>
                            <select id="comment-dropdown-<?php echo $project['id']; ?>" onchange="showComment(this)">
                                <option value="">Select a comment to view...</option>
                                <?php foreach ($comments[$project['id']] as $comment): ?>
                                    <option value="<?php echo htmlspecialchars($comment['comment_text']); ?>">
                                        <?php echo htmlspecialchars($comment['username']); ?>: <?php echo htmlspecialchars($comment['comment_text']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p id="comment-display-<?php echo $project['id']; ?>" style="display:none;"></p>
                        <?php else: ?>
                            <p>No comments yet.</p>
                        <?php endif; ?>

                        <!-- Comment Submission Form -->
                        <form method="POST" action="submit_comment.php">
                            <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                            <label for="comment_text">Add a Comment:</label>
                            <textarea name="comment_text" required></textarea>
                            <button type="submit">Submit Comment</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showComment(select) {
            const projectId = select.id.split('-')[2];
            const selectedComment = select.value;
            const commentDisplay = document.getElementById(`comment-display-${projectId}`);

            if (selectedComment) {
                commentDisplay.textContent = selectedComment;
                commentDisplay.style.display = 'block';
            } else {
                commentDisplay.style.display = 'none';
            }
        }
    </script>
    <style>
        .star-rating label {
            font-size: 20px;
            color: #ccc;
            cursor: pointer;
        }
        .star-rating input:checked ~ label {
            color: #c36217;
        }
        .star-rating input:hover ~ label {
            color: #c36217;
        }
    </style>
</body>
</html>










