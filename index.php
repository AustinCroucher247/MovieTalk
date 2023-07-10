<?php
session_start();
include 'fetch_movies.php'; 
include 'navbar.php';
$page = $_GET['page'] ?? 'home';
$imagePath = "../movie-site/assets/movietalk.png";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.themoviedb.org/3/movie/popular?language=en-US&page=1",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_SSL_VERIFYPEER => false, 
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIwNDRmYWI5OTYyOTU0NGZiZmQyOWUzN2NlOWE1YmNhZiIsInN1YiI6IjYzZjc3Zjc3M2Q3NDU0MDA4ZWZkNGUxZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.2Y7fjcFvPWCQYdCNYduhuKjqnOmUQLAjYX09J0nShQA",
        "accept: application/json"
      ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$results = [];

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $data = json_decode($response, true);
    $results = $data['results'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<style>
        .card-img-top {
            max-width: 150px;
            max-height: 300px;
            margin: 1rem auto; /* Centers image and adds spacing */
            display: block; /* Makes sure the image is block level to respond to the margin: auto; */
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <title>Movie Talk - Home</title>
</head>
<body>

<?php
if (isset($_SESSION['username'])) {
    echo "<div id='welcome-msg'><h2 class='text-center mt-5'>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h2></div>";
}
?>

<!-- Page Content -->
<p class="text-center mt-5 h1">A place to discuss different movies and TV shows with others!</p>
<form action="search-results.php" method="get" class="input-group h-50 mt-5 d-flex align-items-center justify-content-center needs-validation column flex-column">
    <img class="w-50 mr-5" src="<?php echo $imagePath; ?>" alt="Image" />
    <input name="keyword" type="search" class="rounded w-50 p-3 text-center needs-validation" placeholder="Type Movie or TV Show" aria-label="Search" aria-describedby="search-addon" required />
    <button type="submit" class="btn btn-outline-primary w-15 mt-2 needs-validation">Search</button>
</form>
<h1 class="text-center">POPULAR MOVIES</h1>

<div id="carouselExample" class="carousel slide mt-5" data-ride="carousel">
    <div class="carousel-inner">
    <?php for ($i = 0; $i < count($results); $i += 3) { ?>
    <div class="carousel-item <?php echo ($i === 0) ? 'active' : ''; ?>" data-interval="5000">
        <div class="row">
            <?php 
            $count = 0;
            for ($j = $i; $j < $i + 3 && $j < count($results); $j++) {
                // Skip the movie if the primary image is null
                if ($results[$j]['poster_path'] === null) continue;
                $count++;
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://image.tmdb.org/t/p/w500<?php echo $results[$j]['poster_path']; ?>" class="card-img-top" alt="Movie Poster">
                        <div class="card-body text-center">
                            <h5 class="card-title text-center"><?php echo $results[$j]['title']; ?></h5>
                            <a href="movie-detail.php?id=<?php echo urlencode($results[$j]['id']); ?>">
                                <button class="btn btn-primary">View Details</button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                if($count == 3) break; // Exit the loop once 3 movies have been displayed
            } 
            ?>
        </div>
    </div>
<?php } ?>

    <!-- </div>
    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="false"></span>
        <span class="sr-only">Next</span>
    </a>
</div> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

<script>
    window.onload = function() {
        setTimeout(function() {
            var element = document.getElementById("welcome-msg");
            if (element) {
                element.style.transition = "opacity 1s";
                element.style.opacity = "0";
                setTimeout(function() {
                    element.style.display = "none";
                }, 1000); // Wait for the fade-out transition to complete before hiding the element
            }
        }, 1500);
    };
</script>
