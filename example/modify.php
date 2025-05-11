<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopping_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture order details from POST request
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Reduce product availability
$update_availability = "UPDATE availability 
                        SET available_quantity = available_quantity - $quantity 
                        WHERE product_id = $product_id";

if ($conn->query($update_availability) === TRUE) {
    echo "Availability updated successfully.";
} else {
    echo "Error updating availability: " . $conn->error;
}

$conn->close();
?>
