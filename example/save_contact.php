<?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (empty)
$database = "nostrabikes";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prevent SQL Injection
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $message = mysqli_real_escape_string($conn, $message);

    // Insert data into database
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!'); window.location.href='con.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
