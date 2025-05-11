<?php
include('D:\xampp\htdocs\example\dp.php');

$search_term = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

// Search for products based on the search term
$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
$stmt->execute([$search_term, $search_term]);
$products = $stmt->fetchAll();

echo json_encode($products);
?>
