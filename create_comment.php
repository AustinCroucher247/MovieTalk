<?php
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

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $comment = $_POST['comment'];
    $username = $_POST['username'];

    // Sanitize the comment and username text
    $comment = mysqli_real_escape_string($conn, $comment);
    $username = mysqli_real_escape_string($conn, $username);

    // Insert the comment into the database
    $sql = "INSERT INTO comments (post_detail_id, comment, username, created_at) VALUES ('$postId', '$comment', '$username', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Comment added successfully, redirect back to the post page
        header("Location: post.php?post_id=$postId");
        exit();
    } else {
        echo "Error adding comment: " . $conn->error;
    }
}

$conn->close();
?>
