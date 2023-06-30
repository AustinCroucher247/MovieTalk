<?php
session_start();
include 'fetch_movies.php'; 
include 'navbar.php';
$page = $_GET['page'] ?? 'home';
$imagePath = "../movie-site/assets/movietalk.png";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
