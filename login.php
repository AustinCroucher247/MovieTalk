<?php
session_start(); // Start the session at the very beginning

include 'db_connection.php';
include 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // user found
        $user = $result->fetch_assoc(); // Fetch user data

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username']; // Store user's data in session
            header("Location: index.php?page=profile"); // Redirect to profile page
            exit();
        } else {
            echo "Password is incorrect";
        }
    } else {
        echo "No user found";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<body>

<h2 class="input-group d-flex align-items-center justify-content-center column flex-column mt-5" >Login Form</h2>

<form class="input-group d-flex align-items-center justify-content-center column flex-column" method="post" action="">
  Username:<br>
  <input type="text" name="username">
  <br>
  Password:<br>
  <input type="password" name="password">
  <br><br>
  <input type="submit" value="Login">
</form> 

</body>
</html>