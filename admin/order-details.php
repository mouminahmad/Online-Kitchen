<?php
include('partials/menu.php'); // Include the admin menu

// Check if order_id is provided
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order information along with user details
    $sql_order = "SELECT c.order_id, c.total_price, c.order_date, c.status, c.voucher_status, 
                         u.name AS user_name, u.email AS user_email, u.phone AS user_phone
                  FROM checkout c
                  JOIN users u ON c.user_id = u.user_id
                  WHERE c.order_id = ?";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param('i', $order_id);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();
    $order = $result_order->fetch_assoc();

    // Fetch all items in the order
    $sql_items = "SELECT ci.product_id, ci.quantity, ci.price, p.product_name
                  FROM checkout_items ci
                  JOIN products p ON ci.product_id = p.product_id
                  WHERE ci.order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param('i', $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
} else {
    echo "Order ID is missing.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .details-section { margin-bottom: 20px; }
        .details-section h2 { font-size: 1.2em; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

<h1>Order Details</h1>

<div class="details-section">
    <h2>Order Information</h2>
    <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
    <p><strong>User Name:</strong> <?php echo $order['user_name']; ?></p>
    <p><strong>User Email:</strong> <?php echo $order['user_email']; ?></p>
    <p><strong>User Phone:</strong> <?php echo $order['user_phone']; ?></p>
    <p><strong>Total Price:</strong> <?php echo number_format($order['total_price'], 2); ?></p>
    <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
    <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
    <p><strong>Voucher Status:</strong> <?php echo $order['voucher_status']; ?></p>
</div>

<div class="details-section">
    <h2>Ordered Items</h2>
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (Each)</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result_items->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $item['product_id']; ?></td>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<a href="admin-orders.php">Back to Orders</a>

</body>
</html>

<?php $conn->close(); ?>
