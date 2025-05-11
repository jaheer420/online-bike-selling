<?php
include('D:\xampp\htdocs\example\dp.php');

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product already exists in the cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $existing_item = $stmt->fetch();

    if ($existing_item) {
        // Update the quantity if the item already exists
        $new_quantity = $existing_item['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
        $stmt->execute([$new_quantity, $product_id]);
    } else {
        // Insert new item into the cart
        $stmt = $pdo->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");
        $stmt->execute([$product_id, $quantity]);
    }

    echo "Item added to cart successfully!";
}
?>
