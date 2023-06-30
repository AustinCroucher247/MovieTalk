<?php 
 include 'navbar.php';
 ?>

<div class="container">
    <?php
    
    switch ($page) {
        case 'profile':
            require 'profile.php';
            break;
        default:
            ?>
            <div class="row">
                <!-- Movie Search Section -->
                <div class="col-md-8">
                    <h1 class="my-4">Popular Movies</h1>
                    <?php if (!empty($movies)): ?>
                        <?php foreach ($movies as $movie): ?>
                            <div>
                                <h2><?php echo $movie['title']; ?></h2>
                                <p>IMDB ID: <?php echo $movie['imdb_id']; ?></p>
                                <p>Rating: <?php echo $movie['rating']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No popular movies found.</p>
                    <?php endif; ?>
                </div>
        
                <!-- User Profile Info Section -->
                <div class="col-md-4">
                    <h1 class="my-4">Your Profile</h1>
                    <!-- User Info Displayed Here -->
                </div>
            </div>
            <?php
        }
        ?>
    </div>