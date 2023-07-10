<?php
 include 'navbar.php';
//  $keyword = $_GET['keyword'] ?? '';
// // $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');

// $curl = curl_init();

// $apiKey = 'af5beb3b7amsh886735e8fbec057p1e1f6ejsn0d6b7f4d018f';
// $keyword = $_GET['keyword'] ?? '';
// $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
// $keyword = str_replace(' ', '%20', $keyword);
// $url = 'https://moviesdatabase.p.rapidapi.com/titles/search/title/' . $keyword . '?exact=false&titleType=movie';
// curl_setopt_array($curl, [
//     CURLOPT_URL => $url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => "",
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 30,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => "GET",
//     CURLOPT_SSL_VERIFYPEER => false, 
//     CURLOPT_SSL_VERIFYHOST => false,
//     CURLOPT_HTTPHEADER => [
//         "X-RapidAPI-Host: moviesdatabase.p.rapidapi.com",
//         "X-RapidAPI-Key: $apiKey"
//     ],
// ]);

// $response = curl_exec($curl);
// $err = curl_error($curl);

// $movies = [];
// if ($err) {
//     echo "cURL Error #:" . $err;
// } else {
//     $movies = json_decode($response, true);
// }
// if (isset($movies['results'])) {
//     foreach ($movies['results'] as $movie) {
//         // ...
//     }
// } else {
//     echo 'No results found.';
// }
// $page = $_GET['page'] ?? 1;
// $nextPage = $page + 1;

$curl = curl_init();

$searchKeyword = urlencode($_GET['keyword'] ?? '');
$apiKey = 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIwNDRmYWI5OTYyOTU0NGZiZmQyOWUzN2NlOWE1YmNhZiIsInN1YiI6IjYzZjc3Zjc3M2Q3NDU0MDA4ZWZkNGUxZCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.2Y7fjcFvPWCQYdCNYduhuKjqnOmUQLAjYX09J0nShQA';
$url = "https://api.themoviedb.org/3/search/movie?query={$searchKeyword}&include_adult=false&language=en-US&page=1";

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
    "Authorization: $apiKey",
    "accept: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $movies = json_decode($response, true);
}

$page = $_GET['page'] ?? 1;
$nextPage = $page + 1;
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
    <h1 class="mb-4">Search Results for "<?php echo $_GET['keyword']; ?>"</h1>
    <div class="row">
      <?php foreach ($movies['results'] as $movie): ?>
        <?php if (isset($movie['poster_path'], $movie['title'], $movie['release_date'])): ?>
          <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
              <img class="card-img-top" src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="Movie Poster">
              <div class="card-body">
                <a href="movie-detail.php?id=<?php echo urlencode($movie['id']); ?>">
                  <h5 class="card-title"><?php echo $movie['title']; ?></h5>
                  <p class="card-text">Release Date: <?php echo $movie['release_date']; ?></p>
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="container mt-5">
      <?php if (count($movies['results']) > 0): ?>
        <div class="text-center">
          <a href="?keyword=<?php echo urlencode($_GET['keyword']); ?>&page=<?php echo $nextPage; ?>" class="btn btn-primary">Next Page</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>