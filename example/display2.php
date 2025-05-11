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

// Add product_id column to orders table if not exists
$conn_shopping->query("ALTER TABLE orders ADD COLUMN product_id INT NOT NULL AFTER id, ADD FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE");

// Create availability table if not exists
$conn_shopping->query("CREATE TABLE IF NOT EXISTS availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    available_quantity INT NOT NULL,
    available_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)");

// Check if tables exist before fetching data
$tables_shopping = ['orders', 'availability', 'products'];
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
$result_orders = in_array('orders', $existing_tables_shopping) ? $conn_shopping->query("SELECT o.*, p.id AS product_id FROM orders o JOIN products p ON o.product_name = p.product_name") : null;

// Fetch user carted products if table exists
$result_cart = in_array('fury', $existing_tables_jungle) ? $conn_jungle->query("SELECT * FROM fury") : null;

// Fetch product availability with product names if tables exist
$result_availability = null;
if (in_array('availability', $existing_tables_shopping) && in_array('products', $existing_tables_shopping)) {
    $result_availability = $conn_shopping->query(
        "SELECT p.product_name, a.available_quantity, a.available_price 
        FROM availability a 
        LEFT JOIN products p ON a.product_id = p.id"
    );
}
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
        <tr><th>Product Name</th><th>Available Quantity</th><th>Available Price</th></tr>
        <?php while ($row = $result_availability->fetch_assoc()) { ?>
            <tr>
                <td><?php echo isset($row['product_name']) ? $row['product_name'] : 'Unknown Product'; ?></td>
                <td><?php echo $row['available_quantity']; ?></td>
                <td><?php echo $row['available_price']; ?></td>
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
