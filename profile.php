<?php
session_start(); // Start the session at the very beginning

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get user data from the database. You would want to replace this with a real query to your database
$user = [
    'name' => 'John Doe',
    'username' => $_SESSION['username'], // Use the session variable
    'email' => 'johndoe@example.com',
];

// The code for getting movie data
$movies = [
    ['title' => 'Inception', 'year' => 2010],
    ['title' => 'The Dark Knight', 'year' => 2008],
    ['title' => 'Interstellar', 'year' => 2014],
];
?>

<!-- The rest of the HTML code -->

<div class="card mb-4">
    <div class="card-body">
        <h2 class="card-title"><?php echo $user['name']; ?></h2>
        <p class="card-text">
            Username: <?php echo $user['username']; ?><br> <!-- This should display the logged in user's username -->
            Email: <?php echo $user['email']; ?>
        </p>
    </div>
</div>

<h3>Favorite Movies</h3>

<?php foreach ($movies as $movie): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title"><?php echo $movie['title']; ?></h4>
            <p class="card-text">Year: <?php echo $movie['year']; ?></p>
        </div>
    </div>
<?php endforeach; ?>