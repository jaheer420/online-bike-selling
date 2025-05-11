<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "jungle");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add product
if (isset($_POST['add_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $available_quantity = $_POST['available_quantity'];
    $price = $_POST['price'];
    $product_image = $_POST['product_image'];
    
    // Insert into availability table
    $sql1 = "INSERT INTO availability (product_id, product_name, available_quantity) VALUES ('$product_id', '$product_name', '$available_quantity')";
    
    // Insert into fury table with quantity always 1
    $sql2 = "INSERT INTO fury (product_id, product_name, product_image, price, quantity, total_price) VALUES ('$product_id', '$product_name', '$product_image', '$price', 1, '$price')";
    
    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Edit fury table
if (isset($_POST['edit_fury'])) {
    $product_id = $_POST['product_id'] ?? '';
    $product_name = $_POST['product_name'] ?? '';
    $price = $_POST['price'] ?? '';
    $product_image = $_POST['product_image'] ?? '';
    
    if ($product_name && $price && $product_image) {
        $sql = "UPDATE fury SET product_name='$product_name', product_image='$product_image', price='$price', total_price='$price' WHERE product_id='$product_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Fury product updated successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "All fields are required!";
    }
}

// Modify availability table
if (isset($_POST['modify_availability'])) {
    $product_id = $_POST['product_id'] ?? '';
    $product_name = $_POST['product_name'] ?? '';
    $available_quantity = $_POST['available_quantity'] ?? '';
    
    if ($product_name && $available_quantity) {
        $sql = "UPDATE availability SET product_name='$product_name', available_quantity='$available_quantity' WHERE product_id='$product_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Availability product updated successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "All fields are required!";
    }
}

// Fetch products
$result_availability = $conn->query("SELECT * FROM availability");
$result_fury = $conn->query("SELECT * FROM fury");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background: white;
            padding: 15px;
            margin: 20px auto;
            width: 50%;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, button {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        img {
            width: 50px;
            height: auto;
        }
    </style>
</head>
<body>
    <h2>Add New Product</h2>
    <form method="POST">
        <input type="text" name="product_id" placeholder="Product ID" required>
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="number" name="available_quantity" placeholder="Available Quantity" required>
        <input type="text" name="price" placeholder="Price" required>
        <input type="text" name="product_image" placeholder="Product Image URL" required>
        <button type="submit" name="add_product">Add Product</button>
    </form>
    
    <h2>Available Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Available Quantity</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result_availability->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['available_quantity']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>" required>
                    <input type="number" name="available_quantity" value="<?php echo $row['available_quantity']; ?>" required>
                    <button type="submit" name="modify_availability">Modify</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <h2>Fury Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result_fury->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><img src="<?php echo $row['product_image']; ?>"></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="text" name="product_name" placeholder="Product Name" required>
                    <input type="text" name="price" placeholder="Price" required>
                    <input type="text" name="product_image" placeholder="Product Image URL" required>
                    <button type="submit" name="edit_fury">Edit</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
