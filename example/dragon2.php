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

// Handle Buy Now button (Save data into `product` table)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy_now'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $quantity = $_POST['quantity']; // User-selected quantity

    // Fetch product details from `fury` table
    $fetch_sql = "SELECT * FROM fury WHERE product_id = $product_id";
    $fetch_result = $conn->query($fetch_sql);
    if ($fetch_result->num_rows > 0) {
        $row = $fetch_result->fetch_assoc();

        $product_name = $row['product_name'];
        $price = $row['price'];
        $product_image = $row['product_image'];

        // **Correct Calculation**: total_price = price * quantity
        $total_price = $price * $quantity;

        // Insert data into `product` table without deleting from `fury`
        $insert_sql = "INSERT INTO product (product_id, product_name, quantity, price, total_price, name, mobile, address, state, product_image)
                       VALUES ('$product_id', '$product_name', '$quantity', '$price', '$total_price', '$name', '$mobile', '$address', '$state', '$product_image')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "<script>alert('Purchase recorded successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>"; 
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
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
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        .buy-btn {
            background-color: #f44336;
            color: white;
        }
        .buy-btn:hover {
            background-color: #d32f2f;
        }
        .buy-form {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function showBuyForm(id) {
            document.getElementById("buy-form-" + id).style.display = "block";
        }
    </script>
</head>
<body>
    <h1>Shopping Cart</h1>

    <?php
    // Reconnect to MySQL to fetch the updated data
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
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1">
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
     <!-- #region 
      
     
     
     
     
     
     
     
     --><?php
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

/* Textbox size matching product box */
.buy-form input[type="text"],
.buy-form input[type="number"] {
    max-width: 100%;
    box-sizing: border-box;
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

.products-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    width: 100%;
    max-width: 1280px;
    margin: auto;
}

.product {
    flex: 1 1 calc(50% - 40px); /* Two items per row with gap */
    margin: 10px;
    box-sizing: border-box;
}

/* Adjust for smaller screens */
@media (max-width: 768px) {
    .product {
        flex: 1 1 100%; /* Single item per row on smaller devices */
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
        echo '<div class="products-wrapper">';
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
                 <!-- Inside the Buy Now Form -->
<div class="buy-form" id="buy-form-<?php echo $row['product_id']; ?>">
    <form method="GET" action="order_success.php">
        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
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
        echo '</div>';
    } else {
        echo "<p>No products available in the cart.</p>";
    }
    $conn->close();
    ?>
</body>
</html>.give most attractive css code for above code. using flexbox.  











\








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
                 <!-- Inside the Buy Now Form -->
<div class="buy-form" id="buy-form-<?php echo $row['product_id']; ?>">
    <form method="GET" action="order_success.php">
        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
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
</html>.I want to display two products details in each rows using flexwrap method in css . only add my needs don't change the code .give attractive css code.









<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "jungle";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$required_params = ['name', 'mobile', 'address', 'state', 'quantity', 'price', 'product_id', 'product_name', 'product_image'];
$missing_params = [];
foreach ($required_params as $param) {
    if (!isset($_GET[$param]) || empty($_GET[$param])) {
        $missing_params[] = $param;
    }
}

if (!empty($missing_params)) {
    echo "<h2 style='color:red;'>Warning: Missing parameters - " . implode(", ", $missing_params) . "</h2>";
}

$name = htmlspecialchars($_GET['name'] ?? "N/A");
$mobile = htmlspecialchars($_GET['mobile'] ?? "N/A");
$address = htmlspecialchars($_GET['address'] ?? "N/A");
$state = htmlspecialchars($_GET['state'] ?? "N/A");
$quantity = (int) ($_GET['quantity'] ?? 1);
$price = (float) ($_GET['price'] ?? 0.0);
$product_id = (int) ($_GET['product_id'] ?? 0);
$product_name = htmlspecialchars($_GET['product_name'] ?? "Unknown Product");
$product_image = htmlspecialchars($_GET['product_image'] ?? "default.png");

$total_price = $price * $quantity;

// 🟢 Store order details into product table, now including name, mobile, address, and state
$insert = $conn->prepare("INSERT INTO product (product_id, product_name, product_image, price, quantity, total_price, name, mobile, address, state) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) 
    ON DUPLICATE KEY UPDATE 
        product_name = VALUES(product_name), 
        product_image = VALUES(product_image), 
        price = VALUES(price),
        quantity = VALUES(quantity),
        total_price = VALUES(total_price),
        name = VALUES(name),
        mobile = VALUES(mobile),
        address = VALUES(address),
        state = VALUES(state)");
$insert->bind_param("issdiissss", $product_id, $product_name, $product_image, $price, $quantity, $total_price, $name, $mobile, $address, $state);
$insert->execute();
$insert->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff7eb3, #ff758c);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .receipt {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 450px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            color: #ff4757;
            font-size: 22px;
            font-weight: 600;
        }
        img {
            max-width: 120px;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .details {
            text-align: left;
            margin-top: 15px;
            padding: 10px;
            border-radius: 10px;
            background: #f8f8f8;
        }
        .details p {
            font-size: 16px;
            margin: 8px 0;
            color: #333;
        }
        .highlight {
            font-weight: bold;
            color: #ff4757;
        }
        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            color: white;
            background: #ff4757;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(255, 71, 87, 0.3);
        }
        .btn:hover {
            background: #e84118;
            transform: translateY(-2px);
        }
        @media (max-width: 600px) {
            .receipt { width: 95%; padding: 20px; }
            .details p { font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h2>Your Order was Placed Successfully! 🎉</h2>
        <img src="<?php echo 'uploads/' . $product_image; ?>" alt="Product Image" onerror="this.onerror=null;this.src='uploads/default.png';">
        <div class="details">
            <p><strong>Customer Name:</strong> <span class="highlight"><?php echo $name; ?></span></p>
            <p><strong>Mobile:</strong> <span class="highlight"><?php echo $mobile; ?></span></p>
            <p><strong>Address:</strong> <span class="highlight"><?php echo $address; ?>, <?php echo $state; ?></span></p>
            <p><strong>Product:</strong> <span class="highlight"><?php echo $product_name; ?></span></p>
            <p><strong>Quantity:</strong> <span class="highlight"><?php echo $quantity; ?></span></p>
            <p><strong>Price per unit:</strong> <span class="highlight">$<?php echo number_format($price, 2); ?></span></p>
            <p><strong>Total Price:</strong> <span class="highlight">$<?php echo number_format($total_price, 2); ?></span></p>
        </div>
        <a href="index.html" class="btn">Back to Home</a>
    </div>
</body>
</html>
