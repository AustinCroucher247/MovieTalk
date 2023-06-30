<?php
session_start();

if (!isset($_SESSION['username'])) {
    die("Please log in first");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method");
}

if (!isset($_POST['comment_id'])) {
    die("No comment id provided");
}

$commentId = $_POST['comment_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify if the user is the one who posted the comment
$sql = "SELECT username FROM comments WHERE id = '$commentId'";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    die("Comment not found");
}
$row = $result->fetch_assoc();
if ($_SESSION['username'] !== $row['username']) {
    die("You are not allowed to delete this comment");
}

$sql = "DELETE FROM comments WHERE id = '$commentId'";
if ($conn->query($sql) === TRUE) {
    header("Location: post.php"); // Or wherever you want to redirect after deletion
} else {
    echo "Error deleting comment: " . $conn->error;
}

$conn->close();
?>