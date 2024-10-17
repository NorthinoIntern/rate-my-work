<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];  // Assuming user login is implemented

    // Insert the comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (project_id, user_id, comment_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $project_id, $user_id, $comment_text);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the homepage or the page where the comment was submitted
    header("Location: index.php");
    exit();
}
    