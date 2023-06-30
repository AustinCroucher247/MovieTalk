<?php
 include 'navbar.php';
 $keyword = $_GET['keyword'] ?? '';
// $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');

$curl = curl_init();

$apiKey = 'af5beb3b7amsh886735e8fbec057p1e1f6ejsn0d6b7f4d018f';
$keyword = $_GET['keyword'] ?? '';
$keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
$keyword = str_replace(' ', '%20', $keyword);
$url = 'https://moviesdatabase.p.rapidapi.com/titles/search/title/' . $keyword . '?exact=true&titleType=movie';
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
        "X-RapidAPI-Host: moviesdatabase.p.rapidapi.com",
        "X-RapidAPI-Key: $apiKey"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

$movies = [];
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $movies = json_decode($response, true);
}
if (isset($movies['results'])) {
    foreach ($movies['results'] as $movie) {
        // ...
    }
} else {
    echo 'No results found.';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <title>Search Results</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Your navigation bar here -->
    </nav>

    <!-- Search Results -->
    <div class="container mt-5">
    <h1 class="mb-4">Search Results for "<?php echo $keyword; ?>"</h1>
    <div class="row">
    <?php foreach ($movies['results'] as $movie): ?>
    <?php if (isset($movie['primaryImage']['url'], $movie['titleText']['text'], $movie['titleType']['text'], $movie['releaseYear']['year'], $movie['originalTitleText']['text'])): ?>
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem; ">
                <img class="card-img-top" src="<?php echo $movie['primaryImage']['url']; ?>" alt="Movie Poster">
                <div class="card-body">
                <a href="movie-detail.php?id=<?php echo urlencode($movie['id']); ?>">

                    <h5 class="card-title"><?php echo $movie['titleText']['text']; ?></h5>
                    <p class="card-text">Type: <?php echo $movie['titleType']['text']; ?></p>
                    <p class="card-text">Release Year: <?php echo $movie['releaseYear']['year']; ?></p>
                    <p class="card-text">Original Title: <?php echo $movie['originalTitleText']['text']; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
    </div>
</div>
</body>
</html>