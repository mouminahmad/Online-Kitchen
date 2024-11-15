<?php
include('partials-front/menu.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['u_id'];

$stmt = $conn->prepare("SELECT order_id, total_price, voucher_number, status, voucher_status, voucher_file_path, order_date, shipping_name, shipping_address, shipping_phone, shipping_charges, delivery_time, total_cooking_time FROM checkout WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
        }

        .order-container {
            max-width: 800px;
            margin: auto;
        }

        .order-card {
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .order-card h2 {
            color: #0056b3;
        }

        .order-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .order-info div {
            flex: 1 1 45%;
            margin: 10px 0;
            color: #666;
        }

        .status {
            font-weight: bold;
        }

        .view-details-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        /* Modal styling */
        #orderModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        #orderModalContent {
            max-width: 600px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 50px auto;
            position: relative;
        }

        #orderModalContent h2 {
            color: #0056b3;
            margin-bottom: 15px;
        }

        #orderModalContent p {
            margin: 10px 0;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4d4d;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
        }

        .upload-section {
            margin-top: 20px;
        }

        .upload-section input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .upload-btn {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
        }

        .download-btn {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
        }

        .payment-btn {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #ffc107;
            color: white;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="order-container">
        <h1>Your Orders</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-card">
                    <div class="order-info">
                        <div><strong>Order ID:</strong> #<?php echo htmlspecialchars($row['order_id']); ?></div>
                        <div><strong>Order Date:</strong> <?php echo htmlspecialchars($row['order_date']); ?></div>
                        <div><strong>Total Price:</strong> RS <?php echo htmlspecialchars(number_format($row['total_price'], 2)); ?></div>
                        <div><strong>Status:</strong> <span class="status"><?php echo htmlspecialchars($row['status']); ?></span></div>
                        <div><strong>Voucher Number:</strong> <?php echo htmlspecialchars($row['voucher_number']); ?></div>
                        <div><strong>Voucher Status:</strong> <span class="status"><?php echo htmlspecialchars($row['voucher_status']); ?></span></div>
                    </div>
                    <?php
                    if ($row['voucher_number'] != 'N/A') {
                        echo '<button class="view-details-btn" onclick=\'showOrderDetails(' . json_encode($row) . ')\'>View Details</button>';
                    }
                    ?>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <div id="orderModal">
        <div id="orderModalContent">
            <button class="close-btn" onclick="closeModal()">×</button>
            <h2>Order Details</h2>
            <p><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
            <p><strong>Order Date:</strong> <span id="modalOrderDate"></span></p>
            <p><strong>Total Price:</strong> RS <span id="modalTotalPrice"></span></p>
            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            <p><strong>Voucher Status:</strong> <span id="modalVoucherStatus"></span></p>
            <h3>Shipping Details</h3>
            <p><strong>Shipping Name:</strong> <span id="modalShippingName"></span></p>
            <p><strong>Shipping Address:</strong> <span id="modalShippingAddress"></span></p>
            <p><strong>Shipping Phone:</strong> <span id="modalShippingPhone"></span></p>
            <p><strong>Shipping Charges:</strong> RS <span id="modalShippingCharges"></span></p>
            <h3>Additional Details</h3>
            <p><strong>Total Charges (with Shipping):</strong> RS <span id="modalTotalCharges"></span></p>
            <p><strong>Delivery Time:</strong> <span id="modalDeliveryTime"></span></p>
            <p><strong>Total Cooking Time:</strong> <span id="modalCookingTime"></span></p>


            <!-- Add the link to generate receipt -->
            <a id="generateReceiptBtn" href="" class="download-btn" target="_blank">Download Receipt</a>


            <div class="upload-section">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="voucher_file" accept=".jpg,.jpeg,.png,.pdf" required>
                    <button type="submit" class="upload-btn">Upload Voucher</button>
                </form>


            </div>


        </div>
    </div>

    <script>
        function showOrderDetails(order) {
            document.getElementById("modalOrderId").innerText = order.order_id;
            document.getElementById("modalOrderDate").innerText = order.order_date;
            document.getElementById("modalTotalPrice").innerText = parseFloat(order.total_price).toFixed(2);
            document.getElementById("modalStatus").innerText = order.status;
            document.getElementById("modalVoucherStatus").innerText = order.voucher_status;
            document.getElementById("modalShippingName").innerText = order.shipping_name;
            document.getElementById("modalShippingAddress").innerText = order.shipping_address;
            document.getElementById("modalShippingPhone").innerText = order.shipping_phone;
            document.getElementById("modalShippingCharges").innerText = parseFloat(order.shipping_charges).toFixed(2);
            document.getElementById("modalTotalCharges").innerText = (parseFloat(order.total_price) + parseFloat(order.shipping_charges)).toFixed(2);
            document.getElementById("modalDeliveryTime").innerText = order.delivery_time;
            document.getElementById("modalCookingTime").innerText = order.total_cooking_time;
            // Set the receipt download link
            document.getElementById("generateReceiptBtn").href = "generate_receipt_html.php?order_id=" + order.order_id;
            document.getElementById("orderModal").style.display = 'block';
        }

        function closeModal() {
            document.getElementById("orderModal").style.display = 'none';
        }
    </script>

</body>

</html>