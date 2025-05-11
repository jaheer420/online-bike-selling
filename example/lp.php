<?php
// Database configuration
$servername = "localhost"; // Database host
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "shopping_db"; // Database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$quantity = $_POST['quantity'];

// Product details (could come from the database)
$product_name = "Product Name"; // For example
$product_price = 280000.00; // For example

// Calculate the total price
$total_price = $product_price * $quantity;

// Insert order into the database
$sql = "INSERT INTO orders (name, email, address, product_name, quantity, total_price) 
        VALUES ('$name', '$email', '$address', '$product_name', '$quantity', '$total_price')";

if ($conn->query($sql) === TRUE) {
    echo "Order placed successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
