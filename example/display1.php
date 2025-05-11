<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname_jungle = "jungle"; // Single database now

// Create connection to jungle database
$conn_jungle = new mysqli($servername, $username, $password, $dbname_jungle);

// Check connection
if ($conn_jungle->connect_error) {
    die("Connection to jungle database failed: " . $conn_jungle->connect_error);
}

// Check if tables exist before fetching data
$tables_jungle = ['availability', 'product', 'fury'];
$existing_tables_jungle = [];

foreach ($tables_jungle as $table) {
    $check_table = $conn_jungle->query("SHOW TABLES LIKE '$table'");
    if ($check_table && $check_table->num_rows > 0) {
        $existing_tables_jungle[] = $table;
    }
}

// Fetch user purchased products from `product` table
$result_purchased = in_array('product', $existing_tables_jungle) ? 
    $conn_jungle->query("SELECT name, mobile, product_name, quantity, total_price FROM product") : null;

// Fetch carted products from `fury` table
$result_cart = in_array('fury', $existing_tables_jungle) ? 
    $conn_jungle->query("SELECT product_name, quantity, total_price FROM fury") : null;

// Fetch product availability from `availability` table
$result_availability = in_array('availability', $existing_tables_jungle) ? 
    $conn_jungle->query("SELECT product_name, available_quantity FROM availability") : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #1E3C72, #2A5298);
    padding: 20px;
    color: white;
    text-align: center;
}

h1 {
    font-size: 2rem;
    margin-bottom: 15px;
}

table {
    width: 90%;
    margin: auto;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
    color: black;
}

th {
    background: #2A5298;
    color: white;
}

tr:hover {
    background: rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>
    <h1>🛒 Purchased Products</h1>
    <?php if ($result_purchased && $result_purchased->num_rows > 0) { ?>
    <table border="1">
        <tr><th>Customer Name</th><th>Mobile</th><th>Product</th><th>Quantity</th><th>Total Price</th></tr>
        <?php while ($row = $result_purchased->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>$<?php echo number_format($row['total_price'], 2); ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php } else { echo "<p>No purchased products available.</p>"; } ?>

    <h1>🛍️ Carted Products</h1>
    <?php if ($result_cart && $result_cart->num_rows > 0) { ?>
    <table border="1">
        <tr><th>Product Name</th><th>Quantity</th><th>Total Price</th></tr>
        <?php while ($row = $result_cart->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>$<?php echo number_format($row['total_price'], 2); ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php } else { echo "<p>No carted products available.</p>"; } ?>

    <h1>📦 Product Availability</h1>
    <?php if ($result_availability && $result_availability->num_rows > 0) { ?>
    <table border="1">
        <tr><th>Product Name</th><th>Available Quantity</th></tr>
        <?php while ($row = $result_availability->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['available_quantity']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php } else { echo "<p>No availability data available.</p>"; } ?>
</body>
</html>

<?php
$conn_jungle->close();
?>
