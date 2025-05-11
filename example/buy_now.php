<?php
// Get product details from GET
$product_id = $_GET['product_id'];
$product_name = $_GET['product_name'];
$product_image = $_GET['product_image'];
$price = $_GET['price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Now</title>
    <style>
        /* Navbar Styling (Sticky at top) */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
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

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #74ebd5, #9face6);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding-top: 80px; /* To prevent content from being hidden behind the navbar */
        }

        .purchase-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }

        .purchase-box img {
            width: 100%;
            max-height: 250px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .purchase-box label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .purchase-box input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .confirm-btn {
            background-color: #4caf50;
            color: white;
            padding: 12px;
            margin-top: 20px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .confirm-btn:hover {
            background-color: #45a049;
        }

        .product-info {
            text-align: center;
            margin-bottom: 15px;
        }

        .product-info p {
            font-size: 1.1rem;
            color: #555;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Nostra</h1>
    <div class="navbar-links">
        <li class="navbar-link"><a href="login.html">Login</a></li>
        <li class="navbar-link"><a href="index.html">Home</a></li>
        <li class="navbar-link"><a href="coll1.html">Category</a></li>
        <li class="navbar-link"><a href="con.html">Contact</a></li>
    </div>
</div>

<div class="purchase-box">
    <h2>Confirm Your Purchase</h2>
    <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>">
    <div class="product-info">
        <h3><?php echo $product_name; ?></h3>
        <p>Price: $<?php echo number_format($price, 2); ?></p>
    </div>
    <form action="order_success.php" method="GET">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
        <input type="hidden" name="product_image" value="<?php echo $product_image; ?>">
        <input type="hidden" name="price" value="<?php echo $price; ?>">

        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Mobile:</label>
        <input type="text" name="mobile" required>

        <label>Address:</label>
        <input type="text" name="address" required>

        <label>State:</label>
        <input type="text" name="state" required>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="1" min="1" required>

        <button type="submit" class="confirm-btn" name="buy_now">Confirm Purchase</button>
    </form>
</div>
</body>
</html>
