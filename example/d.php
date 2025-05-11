<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "jungle"; // Database name

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

// Fetch updated cart items from `fury` table
$result = $conn->query("SELECT * FROM fury");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
       <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #1E3C72, #2A5298);
        min-height: 100vh;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }


        h1 {
            text-align: center;
            color: #444;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .cart-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .product {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 15px 0;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.2s ease-in-out;
        }
        .product:hover {
            transform: scale(1.02);
        }
        .product img {
            width: 120px;
            height: auto;
            border-radius: 10px;
            margin-right: 20px;
        }
        .product-details {
            flex-grow: 1;
        }
        .product h3 {
            font-size: 1.5rem;
            color: #222;
            margin-bottom: 10px;
        }
        .product p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 8px;
        }
        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }
        .update-btn {
            background: #4CAF50;
            color: white;
        }
        .update-btn:hover {
            background: #45a049;
        }
        .buy-btn {
            background: #f44336;
            color: white;
        }
        .buy-btn:hover {
            background: #d32f2f;
        }
        .buy-form {
            display: none;
            margin-top: 10px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .buy-form h4 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #444;
        }
        .buy-form button {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            background: #28a745;
            color: white;
        }
        .buy-form button:hover {
            background: #218838;
        }
    </style>
    <script>
        function showBuyForm(id) {
            document.getElementById("buy-form-" + id).style.display = "block";
        }
    </script>
</head>
<body>
    <h1>🛒 Shopping Cart</h1>

    <div class="cart-container">
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SELECT * FROM fury");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="product">
                    <img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>">
                    <div class="product-details">
                        <h3><?php echo $row['product_name']; ?></h3>
                        <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                            <label>Quantity:</label>
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit" name="update_quantity" class="update-btn">Update Quantity</button>
                            <button type="button" onclick="showBuyForm('<?php echo $row['product_id']; ?>')" class="buy-btn">Buy Now</button>
                        </form>

                        <div class="buy-form" id="buy-form-<?php echo $row['product_id']; ?>">
                            <h4>Enter Your Details</h4>
                            <form method="GET" action="order_success.php">
                                <input type="text" name="name" placeholder="Full Name" required>
                                <input type="text" name="mobile" placeholder="Mobile Number" required>
                                <input type="text" name="address" placeholder="Address" required>
                                <input type="text" name="state" placeholder="State" required>
                                <input type="number" name="quantity" value="1" min="1" required>
                                <button type="submit" name="buy_now">Confirm Purchase</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No products available in the cart.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "jungle"; // Database name

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

    // Prevent invalid quantity
    if ($quantity <= 0) {
        echo "<script>alert('Quantity must be greater than zero.'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        exit;
    }

    // Recalculate total price
    $total_price = $price * $quantity;

    // Update the quantity and total price in the fury table
    $update_sql = "UPDATE fury SET quantity = $quantity, total_price = $total_price WHERE product_id = $product_id";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Quantity updated successfully.'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>"; 
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch updated cart items from `fury` table
$result = $conn->query("SELECT * FROM fury");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to right, #ff9a9e, #fad0c4);
    padding: 20px;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Ensure full-page centering */
    flex-direction: column;
}

h1 {
    text-align: center;
    color: #fff;
    font-size: 2.5rem;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.product {
    background: white;
    width: 90%;
    max-width: 600px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px;
    margin: 15px auto; /* Centers each product horizontally */
    border-radius: 10px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
}

.product img {
    width: 120px;
    height: auto;
    margin-bottom: 10px;
    border-radius: 10px;
}

.product-details h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 5px;
}

.product-details p {
    font-size: 1.1rem;
    color: #555;
    margin: 5px 0;
}

.product input[type="number"] {
    width: 60px;
    padding: 8px;
    font-size: 1rem;
    text-align: center;
    border: 2px solid #ddd;
    border-radius: 5px;
    margin: 10px 0;
}

.product button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    transition: 0.3s ease;
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

/* Buy Form Styling */
.buy-form {
    display: none;
    background: rgba(255, 255, 255, 0.95);
    padding: 20px;
    border-radius: 10px;
    margin-top: 15px;
    width: 100%;
    text-align: left;
}

/* Ensuring Each Input Field is a Block */
.buy-form label {
    display: block;
    font-weight: bold;
    margin: 8px 0 5px;
    font-size: 1rem;
}

.buy-form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

/* Adjusting the Confirm Purchase Button */
.buy-form button {
    width: 100%;
    padding: 12px;
    font-size: 1.1rem;
    font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product {
        width: 95%;
    }
}

    </style>
    <script>
        function showBuyForm(id) {
            document.getElementById("buy-form-" + id).style.display = "block";
        }
        function updateTotalPrice(input, price, id) {
            let quantity = input.value;
            let totalPrice = price * quantity;
            document.getElementById("total-price-" + id).innerText = "$" + totalPrice.toFixed(2);
        }
    </script>
</head>
<body>
    <h1>Shopping Cart</h1>
    <?php
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
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
                        <button type="button" onclick="showBuyForm('<?php echo $row['product_id']; ?>')" class="buy-btn">Buy Now</button>
                    </form>
                    <!-- Buy Now Form -->
                    <div class="buy-form" id="buy-form-<?php echo $row['product_id']; ?>">
                        <form method="GET" action="order_success.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
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
                            <button type="submit" name="buy_now" class="buy-btn">Confirm Purchase</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No products available in the cart.</p>";
    }
    $conn->close();
    ?>
</body>
</html>