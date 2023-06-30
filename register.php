<?php
include 'db_connection.php';
 include 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    // Hash the password before storing it
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
    
<body>

<h2 class="input-group d-flex align-items-center justify-content-center column flex-column mt-5">Registration Form</h2>

<form class="input-group d-flex align-items-center justify-content-center column flex-column" method="post" action="">
  Username:<br>
  <input type="text" name="username">
  <br>
  Password:<br>
  <input type="password" name="password">
  <br><br>
  <input type="submit" value="Register">
</form> 

</body>
</html>