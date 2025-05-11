<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jungle";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle quantity update in `fury` table
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    if ($quantity <= 0) {
        echo "<script>alert('Quantity must be greater than zero.'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        exit;
    }

    $total_price = $price * $quantity;
    $update_sql = "UPDATE fury SET quantity = $quantity, total_price = $total_price WHERE product_id = $product_id";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Quantity updated successfully.'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>"; 
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Basic Body Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 20px;
        }

        /* Navbar Styling (Non-Sticky) */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
        }

        .navbar-links {
            display: flex;
            gap: 20px;
        }

        .navbar-link {
            list-style: none;
            font-weight: bold;
        }

        .navbar-link a {
            text-decoration: none;
            color: #1d232c;
        }

        .navbar-link:hover a {
            color: #ff3f34;
            text-decoration: underline;
        }

        /* Product Grid */
        .products-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 30px;
        }

        .product {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-left: 65px;
            width: 310px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .product-details h3 {
            margin-top: 15px;
            color: #333;
        }

        .product-details p {
            color: #555;
            margin: 5px 0;
        }

        .product input[type="number"] {
            width: 60px;
            padding: 5px;
            text-align: center;
            margin-top: 10px;
        }

        .product button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .update-btn {
            background-color: #ff9800;
            color: white;
        }

        .update-btn:hover {
            background-color: #e68900;
        }

        .buy-btn {
            background-color: #ff3f34;
            color: white;
        }

        .buy-btn:hover {
            background-color: #d63031;
        }

        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 40px;
        }
    </style>

    <script>
        function updateTotalPrice(input, price, id) {
            let quantity = input.value;
            let total = price * quantity;
            document.getElementById("total-price-" + id).innerText = "$" + total.toFixed(2);
        }
    </script>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Nostra</h1>
    <div class="navbar-links">
        <li class="navbar-link"><a href="login.html">Login</a></li>
        <li class="navbar-link"><a href="index.html">Home</a></li>
        <li class="navbar-link"><a href="coll1.html">Category</a></li>
        <li class="navbar-link"><a href="con.html">Orders</a></li>
    </div>
</div>

<h1>Shopping Cart</h1>

<div class="products-wrapper">
<?php
$conn = new mysqli($servername, $username, $password, $dbname);
$result = $conn->query("SELECT * FROM fury");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
    <div class="product">
        <img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>">
        <div class="product-details">
            <h3><?php echo $row['product_name']; ?></h3>
            <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
            <p>Total Price: <span id="total-price-<?php echo $row['product_id']; ?>">$<?php echo number_format($row['price'], 2); ?></span></p>
            <form method="POST" action="">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" value="1" min="1" oninput="updateTotalPrice(this, <?php echo $row['price']; ?>, <?php echo $row['product_id']; ?>)">
                <button type="submit" name="update_quantity" class="update-btn">Update Quantity</button>
            </form>
            <form method="GET" action="buy_now.php">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <button type="submit" class="buy-btn">Buy Now</button>
            </form>
        </div>
    </div>
<?php
    }
} else {
    echo "<p style='color:white; text-align:center;'>No products available in the cart.</p>";
}
$conn->close();
?>
</div>
</body>
</html>
