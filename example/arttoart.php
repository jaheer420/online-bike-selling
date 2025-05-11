<?php
// Connect to MySQL
$servername = "localhost";
$username = "root"; // default username in XAMPP
$password = ""; // default password in XAMPP
$dbname = "spd"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the art table
$sql = "SELECT * FROM art";
$result = $conn->query($sql);

// Update quantity if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Update the quantity in the database
    $update_sql = "UPDATE art SET quantity = $quantity WHERE product_id = $product_id";
    $conn->query($update_sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .product {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            margin: 10px 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .product img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }
        .product-details {
            flex-grow: 1;
        }
        .product input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-right: 10px;
        }
        .product button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .product button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total_price = $row['price'] * $row['quantity']; // Calculate total price for the product
            ?>
            <div class="product">
                <img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>">
                <div class="product-details">
                    <h3><?php echo $row['product_name']; ?></h3>
                    <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                        <button type="submit" name="update_quantity">Update Quantity</button>
                    </form>
                    <p>Total: $<?php echo number_format($total_price, 2); ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No products available in the cart.</p>";
    }
    ?>

</body>
</html>
