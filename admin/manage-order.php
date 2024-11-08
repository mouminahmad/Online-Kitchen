<?php 
include('partials/menu.php');


// Update order status functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);
    $new_status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    
    if (!empty($order_id) && !empty($new_status)) {
        $sql_update = "UPDATE checkout SET status = ? WHERE order_id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param('si', $new_status, $order_id);
        $stmt->execute();
    }
}

// Update voucher status functionality (admin verifying voucher)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_voucher_status'])) {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);
    $voucher_status = filter_input(INPUT_POST, 'voucher_status', FILTER_SANITIZE_STRING); // 'Verified' or 'Rejected'
    
    if (!empty($order_id) && !empty($voucher_status)) {
        $sql_update_voucher = "UPDATE checkout SET voucher_status = ? WHERE order_id = ?";
        $stmt = $conn->prepare($sql_update_voucher);
        $stmt->bind_param('si', $voucher_status, $order_id);
        $stmt->execute();
    }
}

// Delete order functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);
    
    if (!empty($order_id)) {
        $sql_delete = "DELETE FROM checkout WHERE order_id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
    }
}

// Retrieve orders and items
$sql = "SELECT c.order_id, c.user_id, c.total_price, c.voucher_number, c.isPaid, c.order_date, c.status, c.voucher_status,
               vu.voucher_image, vu.voucher_status AS voucher_upload_status,
               ci.product_id, ci.quantity, ci.price
        FROM checkout c
        LEFT JOIN checkout_items ci ON c.order_id = ci.order_id
        LEFT JOIN voucher_uploads vu ON c.order_id = vu.order_id
        ORDER BY c.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Order Management</title>
    <style>
        /* Styling for the page */
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .button { padding: 5px 10px; text-decoration: none; cursor: pointer; }
        .update-btn { background-color: #4CAF50; color: white; }
        .delete-btn { background-color: #f44336; color: white; }
        .details-btn { background-color: #2196F3; color: white; }
        select { padding: 5px; }
        .voucher-image { max-width: 150px; }
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
            <th>Voucher Number</th>
            <th>Voucher Image</th>
            <th>Voucher Status</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?> 
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo number_format($row['total_price'], 2); ?></td>
                    <td><?php echo $row['voucher_number']; ?></td>
                    <td>
                        <?php if (!empty($row['voucher_image'])): ?>
                            <img src="../images/uploads_vouchers/<?php echo $row['voucher_image']; ?>" alt="Voucher Image" class="voucher-image">
                        <?php else: ?>
                            No image uploaded
                        <?php endif; ?>
                    </td>
                    <td>
                        <!-- Voucher Status dropdown -->
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="voucher_status">
                                <option value="Unverified" <?php if ($row['voucher_status'] == 'Unverified') echo 'selected'; ?>>Unverified</option>
                                <option value="Verified" <?php if ($row['voucher_status'] == 'Verified') echo 'selected'; ?>>Verified</option>
                                <option value="Rejected" <?php if ($row['voucher_status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_voucher_status" class="button update-btn">Update Voucher Status</button>
                        </form>
                    </td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td>
                        <!-- Order Status update -->
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Processing" <?php if ($row['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                <option value="Shipped" <?php if ($row['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="Delivered" <?php if ($row['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="Cancelled" <?php if ($row['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="button update-btn">Update Status</button>
                        </form>
                    </td>
                    <td>
                        <button onclick="showDetails(<?php echo $row['order_id']; ?>)" class="button details-btn">View Details</button>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <button type="submit" name="delete_order" class="button delete-btn" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <tr id="details-<?php echo $row['order_id']; ?>" style="display: none;">
                    <td colspan="8">
                        <!-- Order Item Details -->
                        <table style="width: 100%; margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $order_id = $row['order_id'];
                                $sql_items = "SELECT * FROM checkout_items WHERE order_id = ?";
                                $stmt_items = $conn->prepare($sql_items);
                                $stmt_items->bind_param('i', $order_id);
                                $stmt_items->execute();
                                $result_items = $stmt_items->get_result();
                                while ($item = $result_items->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?php echo $item['product_id']; ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo number_format($item['price'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
// Toggle visibility of order details
function showDetails(orderId) {
    var detailsRow = document.getElementById('details-' + orderId);
    detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
}
</script>

</body>
</html>

<?php $conn->close(); ?>
