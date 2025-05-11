<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname_shopping = "shopping_db";
$dbname_jungle = "jungle";

// Create connection to shopping_db
$conn_shopping = new mysqli($servername, $username, $password, $dbname_shopping);
// Create connection to jungle database
$conn_jungle = new mysqli($servername, $username, $password, $dbname_jungle);

// Check connections
if ($conn_shopping->connect_error) {
    die("Connection to shopping_db failed: " . $conn_shopping->connect_error);
}
if ($conn_jungle->connect_error) {
    die("Connection to jungle database failed: " . $conn_jungle->connect_error);
}

// Check if tables exist before fetching data
$tables_shopping = ['orders', 'availability'];
$tables_jungle = ['fury'];
$existing_tables_shopping = [];
$existing_tables_jungle = [];

foreach ($tables_shopping as $table) {
    $check_table = $conn_shopping->query("SHOW TABLES LIKE '$table'");
    if ($check_table && $check_table->num_rows > 0) {
        $existing_tables_shopping[] = $table;
    }
}

foreach ($tables_jungle as $table) {
    $check_table = $conn_jungle->query("SHOW TABLES LIKE '$table'");
    if ($check_table && $check_table->num_rows > 0) {
        $existing_tables_jungle[] = $table;
    }
}

// Fetch user orders if table exists
$result_orders = in_array('orders', $existing_tables_shopping) ? $conn_shopping->query("SELECT * FROM orders") : null;

// Fetch user carted products if table exists
$result_cart = in_array('fury', $existing_tables_jungle) ? $conn_jungle->query("SELECT * FROM fury") : null;

// Fetch product availability if table exists
$result_availability = in_array('availability', $existing_tables_shopping) ? $conn_shopping->query("SELECT * FROM availability") : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Purchased Products</h1>
    <?php if ($result_orders && $result_orders->num_rows > 0) { ?>
    <table border="1">
        <tr><th>Name</th><th>Email</th><th>Product</th><th>Quantity</th><th>Total Price</th></tr>
        <?php while ($row = $result_orders->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['total_price']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php } else { echo "<p>No orders available.</p>"; } ?>

    <h1>Carted Products</h1>
    <?php if ($result_cart && $result_cart->num_rows > 0) { ?>
    <table border="1">
        <tr><th>Product Name</th><th>Quantity</th><th>Total Price</th></tr>
        <?php while ($row = $result_cart->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['total_price']; ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php } else { echo "<p>No carted products available.</p>"; } ?>

    <h1>Product Availability</h1>
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
$conn_shopping->close();
$conn_jungle->close();
?>
