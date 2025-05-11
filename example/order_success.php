<?php
// Get values from GET
$product_id = $_GET['product_id'];
$product_name = $_GET['product_name'];
$product_image = $_GET['product_image'];
$price = $_GET['price'];
$name = $_GET['name'];
$mobile = $_GET['mobile'];
$address = $_GET['address'];
$state = $_GET['state'];
$quantity = $_GET['quantity'];
$total_price = $price * $quantity;
$purchase_date = date("Y-m-d H:i:s");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jungle";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert into product table
$insert_sql = "INSERT INTO product (product_id, product_name, quantity, price, total_price, name, mobile, address, state, product_image, purchase_date)
               VALUES ('$product_id', '$product_name', '$quantity', '$price', '$total_price', '$name', '$mobile', '$address', '$state', '$product_image', '$purchase_date')";

$conn->query($insert_sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
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
            background: #d4fc79;
            background: linear-gradient(to right, #96e6a1, #d4fc79);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-top: 80px; /* To prevent content from hiding behind navbar */
        }

        .confirmation-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 600px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .confirmation-box img {
            max-width: 200px;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .confirmation-box h2 {
            color: #4caf50;
            margin-bottom: 10px;
        }

        .confirmation-box p {
            font-size: 1.1rem;
            color: #333;
            margin: 5px 0;
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

    <div class="confirmation-box">
        <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>">
        <h2>Order Confirmed!</h2>
        <p><strong>Product:</strong> <?php echo $product_name; ?></p>
        <p><strong>Price:</strong> $<?php echo number_format($price, 2); ?></p>
        <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($total_price, 2); ?></p>
        <hr>
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Mobile:</strong> <?php echo $mobile; ?></p>
        <p><strong>Address:</strong> <?php echo $address; ?>, <?php echo $state; ?></p>
    </div>
</body>
</html>
