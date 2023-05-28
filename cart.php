<?php

include 'db.php';
include 'check_auth.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {

    echo "Error: Invalid CSRF Token!!";

} else if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Get the product details
    $sql = "SELECT name, price FROM products WHERE id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $product_name = $row['name'];
    $product_price = $row['price'];

    // Calculate the total price
    $total_price = $product_price * $quantity;

    // Insert the order
    $user_id = $_SESSION['user_id'];
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO orders (user_id, date) VALUES (:user_id, :date)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $order_id = $conn->lastInsertId();

    // Insert the order details
    $sql = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    
    // Print the confirmation message
    echo "Order confirmed. Thank you for shopping with us!";
    #header('Location: index.php');
    echo "<br>You'll be automatically redirected after 3 seconds...";
    header( "refresh:5;URL=/dvea/index.php" );

    echo "<br><a href='/secure_dvea/index.php'>Go back</a>";
} else {
    echo "Error: product_id and quantity are required.";
}
?>
