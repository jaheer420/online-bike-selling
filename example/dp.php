<?php
$host = 'localhost';
$username = 'root';  // Your MySQL username
$password = '';      // Your MySQL password
$dbname = 'addto_cart';  // Your database name

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
