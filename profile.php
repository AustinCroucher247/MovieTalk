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

include 'navbar.php';

// Get the username from the session
$username = $_SESSION['username'];

// Prepare the SQL statement
$sql = "SELECT * FROM posts WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <h2>Username: <?php echo $username; ?></h2>

        <h3>Posts</h3>
        <?php foreach ($posts as $post): ?>
            <div>
                <h4><?php echo $post['title']; ?></h4>
                <p>Content: <?php echo $post['content']; ?></p>
                <a href="post.php?post_id=<?php echo $post['id']; ?>" class="btn btn-primary">View Post</a>
                <!-- Display other post information as needed -->
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
