<?php
session_start();
include('config/constants.php'); // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate a unique voucher number
    $voucherNumber = uniqid('VCH-');
    $isPaid = false;

    // Calculate the total price, including shipping charge
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['Quantity'];
    }

    // Add shipping charge
    $shippingCharge = 250;
    $total += $shippingCharge; // Add shipping charge to the total

    // Insert into Orders table
    $stmt = $conn->prepare("INSERT INTO checkout (user_id, total_price, voucher_number, isPaid) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $_SESSION['u_id'], $total, $voucherNumber, $isPaid);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the order ID for this order

    // Insert each product into Order_Items table
    $itemStmt = $conn->prepare("INSERT INTO checkout_items  (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id']; // Product ID
        $quantity = $item['Quantity'];
        $price = $item['price'];
        $itemStmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $itemStmt->execute();
    }

    // Close statements
    $stmt->close();
    $itemStmt->close();
    $conn->close();

    // Redirect to order receipt page
    $_SESSION['voucher_number'] = $voucherNumber;
    header("Location: order_receipt.php");
    exit();
}
?>
