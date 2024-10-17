<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $rating_value = $_POST['rating_value'];
    $user_id = $_SESSION['user_id'];  // Assuming user login is implemented

    // Insert the rating into the database
    $stmt = $conn->prepare("INSERT INTO ratings (project_id, user_id, rating_value) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $project_id, $user_id, $rating_value);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the home page (or any other page like index.php)
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate the Work</title>
</head>
<body>
    <h1>Rate the Work</h1>

    <form method="POST" action="rating.php">
        <input type="hidden" name="project_id" value="<?php echo $_GET['project_id']; ?>">
        <div class="star-rating">
            <input type="radio" name="rating_value" value="20" id="star1" required><label for="star1">&#9733;</label>
            <input type="radio" name="rating_value" value="40" id="star2"><label for="star2">&#9733;</label>
            <input type="radio" name="rating_value" value="60" id="star3"><label for="star3">&#9733;</label>
            <input type="radio" name="rating_value" value="80" id="star4"><label for="star4">&#9733;</label>
            <input type="radio" name="rating_value" value="100" id="star5"><label for="star5">&#9733;</label>
        </div>
        <button type="submit">Submit Rating</button>
    </form>

    <style>
        .star-rating label {
            font-size: 40px;
            color: #ccc;
            cursor: pointer;
        }
        .star-rating input:checked ~ label,
        .star-rating input:hover ~ label {
            color: #c36217;
        }
    </style>
</body>
</html>
