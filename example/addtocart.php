<?php
// Include database connection file
include('db_connection.php');

// Check if product_id and quantity are set in the POST request
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if product already exists in the cart
    $query = "SELECT * FROM cart_items WHERE product_id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // If product already exists, update the quantity
        $updateQuery = "UPDATE cart_items SET quantity = quantity + $quantity WHERE product_id = $product_id";
        if ($conn->query($updateQuery) === TRUE) {
            echo "Product quantity updated";
        } else {
            echo "Error updating quantity: " . $conn->error;
        }
    } else {
        // If product doesn't exist in the cart, insert a new record
        $insertQuery = "INSERT INTO cart_items (product_id, quantity) VALUES ($product_id, $quantity)";
        if ($conn->query($insertQuery) === TRUE) {
            echo "Product added to cart";
        } else {
            echo "Error adding product to cart: " . $conn->error;
        }
    }

    // Close connection
    $conn->close();
} else {
    echo "Error: product_id or quantity is missing.";
    exit();
}
?>
