<?php
include('partials/menu.php');

if (isset($_POST['submitted']) && $_POST['submitted'] == 1) {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);
    $shipping_charges = filter_input(INPUT_POST, 'shipping_charges', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $delivery_time = filter_input(INPUT_POST, 'delivery_time', FILTER_SANITIZE_STRING);


    if ($order_id && $shipping_charges && $delivery_time) {
        // Check if voucher already exists
        $stmt = $conn->prepare("SELECT voucher_number FROM checkout WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $existing_voucher = $row['voucher_number'];

            // Check if voucher is NULL or empty
            if (empty($existing_voucher) || $existing_voucher === 'N/A') {
                // Generate a new voucher
                $voucher_number = 'OK-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));

                // Prepare the UPDATE query to save the new voucher
                $stmt = $conn->prepare("UPDATE checkout SET voucher_number = ?, shipping_charges = ?, delivery_time = ? WHERE order_id = ?");
                $stmt->bind_param("sdii", $voucher_number, $shipping_charges, $delivery_time, $order_id);

                if ($stmt->execute()) {
                    // Redirect after successful operation
                    header("Location: manage-order.php");
                    exit();
                } else {
                    echo "<p>Error executing update: " . $stmt->error . "</p>";
                }
            } else {
                // Voucher already exists, handle as needed
                header("Location: manage-order.php");
                exit();
            }
        } else {
            echo "<p>Order not found with ID $order_id</p>";
        }
    }
}











// Retrieve orders
$sql_orders = "
    SELECT 
        c.order_id, 
        c.user_id, 
        c.total_price, 
        c.voucher_number, 
        c.isPaid, 
        c.order_date, 
        c.status, 
        c.voucher_status,
        c.shipping_name,       -- Added shipping name
        c.shipping_address,    -- Added shipping address
        c.shipping_phone,      -- Added shipping phone
        vu.voucher_image, 
        vu.voucher_status AS voucher_upload_status
    FROM checkout c
    LEFT JOIN voucher_uploads vu ON c.order_id = vu.order_id
    ORDER BY c.order_date DESC
";
$result_orders = $conn->query($sql_orders);





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Order Management</title>
    <style>
        /* Basic styling for the table and modal */
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .button {
            padding: 6px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 5px;
            text-align: left;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 30px;
            border-radius: 10px;
            width: 40%;
            border: 1px solid #888;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .voucher-title {
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
            color: #007bff;
        }

        .voucher-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group {
            width: 100%;
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .form-input:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-row {
            display: flex;
            gap: 20px;
            width: 100%;
        }

        .form-group {
            flex: 1;
        }
    </style>
</head>

<body>

    <h1>Admin Order Management</h1>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Price</th>
                <th>Voucher Image</th>
                <th>Voucher Status</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Generate Voucher</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_orders->num_rows > 0): ?>
                <?php while ($row = $result_orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo number_format($row['total_price'], 2); ?></td>
                        <td>
                            <?php if (!empty($row['voucher_image'])): ?>
                                <img src="../images/uploads_vouchers/<?php echo $row['voucher_image']; ?>" alt="Voucher Image" width="50">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <select name="voucher_status">
                                    <option value="Unverified" <?php if ($row['voucher_status'] == 'Unverified') echo 'selected'; ?>>Unverified</option>
                                    <option value="Verified" <?php if ($row['voucher_status'] == 'Verified') echo 'selected'; ?>>Verified</option>
                                    <option value="Rejected" <?php if ($row['voucher_status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                                </select>
                                <button type="submit" name="update_voucher_status" class="button">Update</button>
                            </form>
                        </td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <select name="status">
                                    <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Processing" <?php if ($row['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                    <option value="Shipped" <?php if ($row['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                    <option value="Delivered" <?php if ($row['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                    <option value="Cancelled" <?php if ($row['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="button">Update</button>
                            </form>
                        </td>
                        <td>
                            <button onclick="showDetails(<?php echo $row['order_id']; ?>)" class="button">Details</button>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <button type="submit" name="delete_order" class="button" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                        <td>
                            <?php if ($row['voucher_number'] === 'N/A'): ?>
                                <button onclick="openVoucherForm(
    <?php echo $row['order_id']; ?>, 
    '<?php echo htmlspecialchars($row['shipping_name']); ?>', 
    '<?php echo htmlspecialchars($row['shipping_phone']); ?>', 
    '<?php echo htmlspecialchars($row['shipping_address']); ?>', 
    '<?php echo htmlspecialchars($row['total_price']); ?>'
)" class="button">Generate Voucher</button>
                            <?php else: ?>
                                <span><?php echo $row['voucher_number']; ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Popup Form -->
    <!-- Popup Form for Generating Voucher -->
    <div id="voucherForm" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeVoucherForm()">&times;</span>
            <h2 class="voucher-title">Generate Voucher</h2>
            <form method="post" class="voucher-form">
                <input type="hidden" name="order_id" id="order_id">

                <!-- First Row (Two Columns) -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="customer_name">Customer Name:</label>
                        <p id="customer_name"></p>
                    </div>
                    <div class="form-group">
                        <label for="customer_phone">Phone:</label>
                        <p id="customer_phone"></p>
                    </div>
                </div>

                <!-- Second Row (Two Columns) -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="customer_address">Address:</label>
                        <p id="customer_address"></p>
                    </div>
                    <div class="form-group">
                        <label for="total_charges">Total Bill (excluding shipping):</label>
                        <p id="total_charges"></p>
                    </div>
                </div>

                <!-- Remaining Single-Column Fields -->
                <div class="form-group">
                    <label for="shipping_charges">Shipping Charges:</label>
                    <input type="number" name="shipping_charges" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="delivery_time">Delivery Time :</label>
                    <input type="number" name="delivery_time" class="form-input" required>
                </div>

                <input type="hidden" name="submitted" value="1">
                <button type="submit" class="button">Submit</button>
            </form>
        </div>
    </div>


    <script>
        function openVoucherForm(orderId, shippingName, shippingPhone, shippingAddress, total) {
            // Open modal and prefill data
            console.log(total);

            document.getElementById('voucherForm').style.display = 'block';
            document.getElementById('order_id').value = orderId;
            document.getElementById('customer_name').innerText = shippingName; // Corrected this line
            document.getElementById('customer_phone').innerText = shippingPhone;
            document.getElementById('customer_address').innerText = shippingAddress;
            document.getElementById('total_charges').innerText = total;
        }

        function closeVoucherForm() {
            document.getElementById('voucherForm').style.display = 'none';
        }
    </script>

</body>

</html>