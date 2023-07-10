<?php
session_start();

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

// Retrieve the form data
$title = $_POST['title'];
$content = $_POST['content'];
$movieId = $_POST['movie_id'];

// Retrieve the username from the session
$username = $_SESSION['username'];

// Prepare the SQL statement
$sql = "INSERT INTO posts (title, content, movie_id, username) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind the parameters and execute the statement
$stmt->bind_param("ssss", $title, $content, $movieId, $username);
$stmt->execute();

// Check if the insertion was successful
if ($stmt->affected_rows > 0) {
    // Redirect back to the movie detail page
    header("Location: movie-detail.php?id=$movieId");
    exit();
} else {
    echo "Error creating post: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
