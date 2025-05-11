<?php
// Include database connection file
include('db_connection.php');

// Fetch cart items and calculate the total price
$query = "SELECT p.price, ci.quantity FROM cart_items ci JOIN products1 p ON ci.product_id = p.id";
$result = $conn->query($query);

$total = 0;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total += $row['price'] * $row['quantity'];
    }
    echo "Total: " . number_format($total, 2);
} else {
    echo "Your cart is empty";
}

// Close connection
$conn->close();
?>
