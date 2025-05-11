<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_system"; // Corrected database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user inputs
$username = $_POST['username'];
$password = $_POST['password'];

// Fetch user from database
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        header("Location: index.html"); // Redirect on successful login
    } else {
        header("Location: login.html?error=1"); // Incorrect password
    }
} else {
    header("Location: login.html?error=1"); // Username not found
}

// Close connection
$conn->close();
?>
