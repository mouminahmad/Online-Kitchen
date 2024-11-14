<?php
// process_order.php
session_start();
include('config/constants.php'); // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isPaid = false;

    // Retrieve shipping information from POST data
    $shipping_Name = $_POST['shipping_name'];
    $shipping_Phone = $_POST['shipping_phone'];
    $shipping_Address = $_POST['shipping_address'];

    // Calculate the total price, including shipping charge
    $total = 0;
    $totalCookingTime = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['Quantity'];
        $totalCookingTime += $item['cooking_time'] ; // Assuming each item has a 'cooking_time' property
        
    }
        // Insert into Orders table without voucher number
        $stmt = $conn->prepare("INSERT INTO checkout (user_id, total_price, isPaid, shipping_name, shipping_phone, shipping_address, total_cooking_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idssssi", $_SESSION['u_id'], $total, $isPaid, $shipping_Name, $shipping_Phone, $shipping_Address, $totalCookingTime);
        $stmt->execute();



        $order_id = $stmt->insert_id; // Get the order ID for this order

        // Insert each product into checkout_items table
        $itemStmt = $conn->prepare("INSERT INTO checkout_items (order_id, product_id, quantity, price, total_cooking_time) VALUES (?, ?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $quantity = $item['Quantity'];
            $price = $item['price'];
            $cooking_time = $item['cooking_time'];
            $itemStmt->bind_param("iiidi", $order_id, $product_id, $quantity, $price, $cooking_time);
            $itemStmt->execute();
        }
        

        // Close statements
        $stmt->close();
        $itemStmt->close();
        $conn->close();

        // Clear cart session data
        unset($_SESSION['cart']);

        // Redirect to order receipt page
        header("Location: order_details.php");
        exit();
    }
