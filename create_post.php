<?php
$servername = "localhost";
$username = "root"; // default username for WAMP
$password = ""; // default password for WAMP is empty
$dbname = "movie_db"; // name of your database

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

// Prepare the SQL statement
$sql = "INSERT INTO posts (title, content, movie_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind the parameters and execute the statement
$stmt->bind_param("sss", $title, $content, $movieId);
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