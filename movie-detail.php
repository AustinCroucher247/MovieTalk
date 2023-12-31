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

    include 'navbar.php';

    $apiKey = 'af5beb3b7amsh886735e8fbec057p1e1f6ejsn0d6b7f4d018f';

    // Get the id from the URL
    $movieId = $_GET['id'] ?? '';

    // Sanitize the id to ensure it's safe to include in the URL
    $movieId = htmlspecialchars($movieId, ENT_QUOTES, 'UTF-8');

    $url = 'https://api.themoviedb.org/3/movie/' . $movieId . '?language=en-US';

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIwNDRmYWI5OTYyOTU0NGZiZmQyOWUzN2NlOWE1YmNhZiIsInN1YiI6IjYzZjc3Zjc3M2Q3NDU0MDA4ZWZkNGUxZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.2Y7fjcFvPWCQYdCNYduhuKjqnOmUQLAjYX09J0nShQA",
            "accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    $movie = [];
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $movie = json_decode($response, true);
    }

    // Retrieve posts for the movie from the database
    $sql = "SELECT * FROM posts WHERE movie_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $movie['title']; ?></title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <!-- Movie Image -->
                    <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="Movie Poster" class="img-fluid">
                </div>
                <div class="col-md-6 text-center">
                    <!-- Movie Details -->
                    <h2><?php echo $movie['title']; ?></h2>
                    <p>Type: <?php echo $movie['original_title']; ?></p>
                    <p>Release Date: <?php echo $movie['release_date']; ?></p>
                    <p>Overview: <?php echo $movie['overview']; ?></p>
                    
                    <!-- Post Creation Form -->
                    <form method="POST" action="create_post.php">
                        <div class="form-group">
                            <label for="title">Post Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Post Content</label>
                            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                        </div>
                        <input type="hidden" name="movie_id" value="<?php echo $movieId; ?>">
                        <button type="submit" class="btn btn-primary w-100">Create Post</button>
                        <p class="h2 mt-5">SEE POSTS BELOW!</p>

                    </form>
                </div>
            </div>
        </div>
        <!-- Display Posts -->
        <div class="container mt-4">
            <h3>Posts</h3>
            <?php foreach ($posts as $post): ?>
                <div class="card my-2">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $post['title']; ?></h5>
                        <p class="card-text"><?php echo $post['content']; ?></p>
                        <a href="post.php?post_id=<?php echo $post['id']; ?>" class="btn btn-primary">View Post</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
    </html>
