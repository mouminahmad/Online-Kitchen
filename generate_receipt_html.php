<?php
// Include database configuration
include('config/constants.php'); // Database connection file

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];
    $userId = $_SESSION['u_id'];

    // Fetch order details
    $stmt = $conn->prepare("SELECT * FROM checkout WHERE order_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        // Calculate total amount and total time
        $totalAmount = $order['total_price'] + $order['shipping_charges'];
        $totalTime = $order['delivery_time'] + $order['total_cooking_time'];

        // Prepare the receipt content
        $receiptContent = "
        Order Receipt\n
        =========================\n
        Order ID: " . $order['order_id'] . "\n
        Order Date: " . date("d-m-Y", strtotime($order['order_date'])) . "\n
        Total Price: RS " . number_format($order['total_price'], 2) . "\n
        Order Status: " . ucfirst($order['status']) . "\n\n
        
        Shipping Details:\n
        Name: " . $order['shipping_name'] . "\n
        Address: " . $order['shipping_address'] . "\n
        Phone: " . $order['shipping_phone'] . "\n
        Shipping Cost: RS " . number_format($order['shipping_charges'], 2) . "\n
        Total Amount (Shipping + Price): RS " . number_format($totalAmount, 2) . "\n
        Delivery Time: " . $order['delivery_time'] . " mins\n
        Cooking Time: " . $order['total_cooking_time'] . " mins\n
        Total Time (Delivery + Cooking): " . $totalTime . " mins\n
        =========================\n";

        // Set the download headers for a text file
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="order_receipt_' . $orderId . '.txt"');
        header('Content-Length: ' . strlen($receiptContent));

        // Output the content of the receipt
        echo $receiptContent;
    } else {
        echo "Order not found or you do not have permission to access it.";
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
