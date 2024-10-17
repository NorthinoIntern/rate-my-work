<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $rating_value = $_POST['rating_value'];
    $user_id = $_SESSION['user_id'];  // Assuming user login is implemented

    // Insert the rating into the database or update if it already exists
    $stmt = $conn->prepare("
        INSERT INTO ratings (project_id, user_id, rating_value) 
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE rating_value = ?;
    ");
    $stmt->bind_param("iiis", $project_id, $user_id, $rating_value, $rating_value);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the homepage or the page where the rating was submitted
    header("Location: index.php");
    exit();
}
?>
