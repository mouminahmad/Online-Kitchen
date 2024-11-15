<?php
session_start();
if (!isset($_SESSION['voucher_number'])) {
    header("Location: checkout.php");
    exit();
}
$voucherNumber = $_SESSION['voucher_number'];

// Calculate the total price based on the cart items
$totalAmount = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalAmount += $item['price'] * $item['Quantity'];
    }
}

// Define shipping charge (you can make it dynamic based on cart total or location)
$shipping_charge = 250; // Example fixed shipping charge

// Calculate grand total (total amount + shipping charge)
$grand_total = $totalAmount + $shipping_charge;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; text-align: center; }
        h1 { color: #4CAF50; }
        p { font-size: 18px; }
        .download-buttons { margin-top: 20px; }
        .download-buttons a {
            padding: 10px 20px;
            margin: 5px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .download-buttons a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>Order Receipt</h1>
<p>Thank you for your order! Your voucher number is: <strong><?php echo $voucherNumber; ?></strong></p>
<p><strong>Total Amount: RS <?php echo number_format($totalAmount, 2); ?></strong></p>
<p><strong>Shipping Charges: RS <?php echo number_format($shipping_charge, 2); ?></strong></p>
<p><strong>Grand Total: RS <?php echo number_format($grand_total, 2); ?></strong></p>

<div class="download-buttons">
    <a href="generate_receipt_html.php" target="_blank">Download Receipt</a>
</div>

<form action="upload_voucher.php" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
    <label for="voucher_image">Upload Voucher Image:</label>
    <input type="file" name="voucher_image" id="voucher_image" required>
    <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: #fff; border: none; cursor: pointer;">Upload Voucher</button>
</form>

</body>
</html>
