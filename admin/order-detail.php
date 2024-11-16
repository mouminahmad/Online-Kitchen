<?php
// Assuming you have a database connection
include "partials/menu.php";

// Check if order_id is passed in the URL
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Fetch order details based on order_id
    $sql = "SELECT * FROM checkout WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id); // 'i' stands for integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        echo "No order found.";
        exit();
    }

    // Fetch checkout items details based on order_id
    $sql_checkout_items = "SELECT * FROM checkout_items WHERE order_id = ?";
    $stmt_checkout_items = $conn->prepare($sql_checkout_items);
    $stmt_checkout_items->bind_param("i", $order_id);
    $stmt_checkout_items->execute();
    $checkout_items_result = $stmt_checkout_items->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .order-details, .checkout-details {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
        }
        .order-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item span {
            font-weight: bold;
        }
        .btn-back {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
        .col {
            flex: 1;
            margin-right: 20px;
        }
        .col:last-child {
            margin-right: 0;
        }
        .order-column {
            background-color: #f1f1f1;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .checkout-column {
            background-color: #fefefe;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Order Details for Order ID: <?php echo $order_id; ?></h2>

        <div class="row">
            <div class="col-md-6">
                <div class="order-column">
                    <h3>Order Information</h3>
                    <p><span>Order ID:</span> <?php echo $order['order_id']; ?></p>
                    <p><span>User ID:</span> <?php echo $order['user_id']; ?></p>
                    <p><span>Total Price:</span> <?php echo $order['total_price']; ?></p>
                    <p><span>Shipping Name:</span> <?php echo $order['shipping_name']; ?></p>
                    <p><span>Shipping Address:</span> <?php echo $order['shipping_address']; ?></p>
                    <p><span>Shipping Phone:</span> <?php echo $order['shipping_phone']; ?></p>
                    <p><span>Voucher Number:</span> <?php echo $order['voucher_number']; ?></p>
                    <p><span>Order Date:</span> <?php echo $order['order_date']; ?></p>
                    <p><span>Status:</span> <?php echo $order['status']; ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="checkout-column">
                    <?php
                    // Check if there are any checkout items for this order
                    if ($checkout_items_result && $checkout_items_result->num_rows > 0) {
                        echo "<h3>Order Items</h3>";
                        // Display the order items in a table
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr><th>Item Name</th><th>Quantity</th><th>Price</th><th>Total Price</th><th>Cooking Time</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        // Loop through each item in checkout_items
                        while ($row = $checkout_items_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['quantity'] * $row['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['total_cooking_time']) . " minutes</td>";
                            echo "</tr>";
                        }
                        
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p>No items found for Order ID: $order_id</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="manage-order.php" class="btn-back">Back to Orders</a>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
