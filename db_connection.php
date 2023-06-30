<?php
$servername = "localhost";
$username = "root"; // default username for WAMP
$password = ""; // default password for WAMP is empty
$dbname = "movie_db"; // name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>