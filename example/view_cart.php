<?php
include('D:\xampp\htdocs\example\dp.php');

$stmt = $pdo->prepare("SELECT p.name, p.price, c.quantity, (p.price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id");
$stmt->execute();
$cart_items = $stmt->fetchAll();

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['total'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Shopping Cart</h1>
    <table id="cart">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo '$' . number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo '$' . number_format($item['total'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Total Price: $<?php echo number_format($total_price, 2); ?></h2>
</body>
</html>
