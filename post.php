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

$postId = $_GET['post_id'] ?? '';

$postId = mysqli_real_escape_string($conn, $postId);

$sql = "SELECT * FROM posts WHERE id = '$postId'";
$result = $conn->query($sql);

$post = $result->fetch_assoc();

if (!$post) {
    echo "Post not found";
    exit();
}

$commentsSql = "SELECT * FROM comments WHERE post_detail_id = '$postId' AND parent_id IS NULL";
$commentsResult = $conn->query($commentsSql);

$comments = [];

if ($commentsResult->num_rows > 0) {
    while ($row = $commentsResult->fetch_assoc()) {
        $row['replies'] = [];
        $repliesSql = "SELECT * FROM comments WHERE parent_id = '{$row['id']}'";
        $repliesResult = $conn->query($repliesSql);
        if ($repliesResult->num_rows > 0) {
            while ($reply = $repliesResult->fetch_assoc()) {
                $row['replies'][] = $reply;
            }
        }
        $comments[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        echo "Please log in to post a comment";
    } else {
        $comment = $_POST['comment'];
        $username = $_SESSION['username'];
        $parentId = $_POST['parent_id'] ?? NULL;

        $comment = mysqli_real_escape_string($conn, $comment);

        $insertSql = "INSERT INTO comments (post_detail_id, comment, username, created_at, parent_id) VALUES ('$postId', '$comment', '$username', NOW(), '$parentId')";
        $insertResult = $conn->query($insertSql);

        if ($insertResult) {
            header("Location: post.php?post_id=$postId");
            exit();
        } else {
            echo "Error adding comment: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class=" mb-5">
            <h2 class="text-center"><?php echo $post['title']; ?></h2>
            <p class="text-center p-1 m-1"><?php echo $post['content']; ?></p>
            <p>Created at: <?php echo $post['created_at']; ?></p>

            <h3>Comments</h3>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Username: <?php echo $comment['username']; ?></h6>
                            <p class="card-text"><?php echo $comment['comment']; ?></p>
                            <p class="card-text">Created at: <?php echo $comment['created_at']; ?></p>
                        </div>
                        <form method="POST" action="post.php?post_id=<?php echo $postId; ?>" class="ml-4">
                            <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                            <div class="form-group">
                                <textarea class="form-control" name="comment" rows="2" placeholder="Reply to this comment" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Reply</button>
                        </form>

                        <?php if (!empty($comment['replies'])): ?>
                            <?php foreach ($comment['replies'] as $reply): ?>
                                <div class="card mt-2 ml-5">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">Username: <?php echo $reply['username']; ?></h6>
                                        <p class="card-text"><?php echo $reply['comment']; ?></p>
                                        <p class="card-text">Created at: <?php echo $reply['created_at']; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>

            <h3>Add a Comment</h3>
<?php if (isset($_SESSION['username'])): ?>
    <form method="POST" action="post.php?post_id=<?php echo $postId; ?>">
        <div class="form-group">
            <textarea class="form-control" name="comment" rows="4" placeholder="Enter your comment" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Comment</button>
    </form>
<?php else: ?>
    <p>You must be <a href="login.php">logged in</a> to post a comment.</p>
<?php endif; ?>
        </div>
    </div>
</body>
</html>
