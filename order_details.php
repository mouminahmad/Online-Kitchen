<?php
include('partials-front/menu.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in (assumes there's a user session)
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$userId = $_SESSION['u_id'];

// Fetch all orders for the logged-in user with status and voucher status
$stmt = $conn->prepare("SELECT order_id, total_price, voucher_number, status, voucher_status, order_date FROM checkout WHERE user_id = ? ORDER BY order_date DESC");
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
        /* Inline CSS to style the page */
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; text-align: center; }
        .order-container { max-width: 800px; margin: auto; }
        .order-card { background-color: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .order-card h2 { color: #007bff; margin-top: 0; }
        .order-info { display: flex; justify-content: space-between; }
        .order-info div { margin-bottom: 10px; }
        .status-paid { color: green; font-weight: bold; }
        .status-pending { color: red; font-weight: bold; }
        .status-verified { color: blue; font-weight: bold; }
        .status-rejected { color: orange; font-weight: bold; }
        .status-shipped { color: #007bff; font-weight: bold; }
        .status-processing { color: #ffc107; font-weight: bold; }
        .status-delivered { color: #28a745; font-weight: bold; }
        .status-cancelled { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<div class="order-container">
    <h1 style="margin-bottom: 20px;">My Orders</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="order-card">
                <h2>Order #<?php echo htmlspecialchars($row['order_id']); ?></h2>
                
                <div class="order-info">
                    <div><strong>Date:</strong> <?php echo htmlspecialchars($row['order_date']); ?></div>
                    <div><strong>Total Price:</strong> RS <?php echo htmlspecialchars(number_format($row['total_price'], 2)); ?></div>
                </div>
                
                <div class="order-info">
                    <div><strong>Voucher Number:</strong> <?php echo htmlspecialchars($row['voucher_number']); ?></div>
                    <div>
                        <strong>Status:</strong> 
                        <?php 
                        switch ($row['status']) {
                            case 'Shipped':
                                echo '<span class="status-shipped">Shipped</span>';
                                break;
                            case 'Pending':
                                echo '<span class="status-pending">Pending</span>';
                                break;
                            case 'Processing':
                                echo '<span class="status-processing">Processing</span>';
                                break;
                            case 'Delivered':
                                echo '<span class="status-delivered">Delivered</span>';
                                break;
                            case 'Cancelled':
                                echo '<span class="status-cancelled">Cancelled</span>';
                                break;
                            default:
                                echo '<span class="status-pending">Unknown</span>';
                        }
                        ?>
                    </div>
                    <div>
                        <strong>Voucher Status:</strong> 
                        <?php if ($row['voucher_status'] == 'Verified'): ?>
                            <span class="status-verified">Verified</span>
                        <?php elseif ($row['voucher_status'] == 'Rejected'): ?>
                            <span class="status-rejected">Rejected</span>
                        <?php else: ?>
                            <span class="status-pending">Not Verified</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have no orders.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
<?php include('partials-front/footer.php'); ?>
