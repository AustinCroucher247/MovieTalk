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

function getComments($conn, $postId, $parentId = 0) {
    $comments = [];
    $sql = "SELECT * FROM comments WHERE post_detail_id = '$postId' AND parent_id = '$parentId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['replies'] = getComments($conn, $postId, $row['id']);
            $comments[] = $row;
        }
    }

    return $comments;
}

$comments = getComments($conn, $postId);

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
$result = $conn->query($sql) or die(mysqli_error($conn));
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
            <div class="border"> 
            <h2 class="text-left"><u> <?php echo $post['title']; ?></h2></u>
            <p class="text-left p-1 m-1"><?php echo $post['content']; ?></p>
            <p>Posted at: <?php echo $post['created_at']; ?></p>
            </div>
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

            <h3>Comments</h3>

            <?php function displayComments($comments, $postId) { ?>
                <?php foreach ($comments as $comment): ?>
                    <?php
                    $commentTimestamp = strtotime($comment['created_at']);
                    $currentTimestamp = time();
                    $timeAgo = '';

                    $timeDifference = $currentTimestamp - $commentTimestamp;

                    if ($timeDifference < 60) {
                        $timeAgo = $timeDifference . ' seconds ago';
                    } elseif ($timeDifference < 3600) {
                        $minutes = floor($timeDifference / 60);
                        $timeAgo = $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                    } elseif ($timeDifference < 86400) {
                        $hours = floor($timeDifference / 3600);
                        $timeAgo = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                    } else {
                        $days = floor($timeDifference / 86400);
                        $timeAgo = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                    }
                    ?>
                    <div class="comment">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-subtitle mb-2 text-muted">Username: <?php echo $comment['username']; ?></h6>
                                    <p class="card-text text-right"><?php echo $timeAgo; ?></p>
                                </div>
                                <p class="card-text"><?php echo $comment['comment']; ?></p>
                            </div>
                            <form method="POST" action="post.php?post_id=<?php echo $postId; ?>" class="ml-4">
                                <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                                <div class="form-group">
                                    <textarea class="form-control" name="comment" rows="2" placeholder="Reply to this comment" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Reply</button>
                            </form>
                            <?php if (!empty($comment['replies'])): ?>
                                <div class="ml-5">
                                    <?php displayComments($comment['replies'], $postId); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php } ?>

            <?php displayComments($comments, $postId); ?>

        </div>
    </div>
</body>
</html>
