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
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
$phone = $_POST['phone'];
$email = $_POST['email'];

// Insert user into database
$sql = "INSERT INTO users (username, password, phone, email) VALUES ('$username', '$password', '$phone', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful! <a href='login.html'>Login here</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
