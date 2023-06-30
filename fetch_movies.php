<?php

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://moviesminidatabase.p.rapidapi.com/movie/order/byRating/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false, 
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Host: moviesminidatabase.p.rapidapi.com",
        "X-RapidAPI-Key: af5beb3b7amsh886735e8fbec057p1e1f6ejsn0d6b7f4d018f"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$movies = [];

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $responseData = json_decode($response, true);

    if (isset($responseData['results'])) {
        $movies = $responseData['results'];
    }
}
?>

<!-- Carousel -->
<div id="movieCarousel" class="carousel slide" data-ride="carousel">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php foreach ($movies as $index => $movie): ?>
            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                <div class="carousel-caption">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Arrow Buttons -->
    <a class="carousel-control-prev" href="#movieCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#movieCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>