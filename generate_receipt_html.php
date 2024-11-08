<?php
session_start();

if (!isset($_SESSION['voucher_number']) || !isset($_SESSION['cart'])) {
    header("Location: checkout.php");
    exit();
}

$voucherNumber = $_SESSION['voucher_number'];
$cartItems = $_SESSION['cart'];
$totalPrice = 0;

foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['Quantity'];
}

// Send headers to download as .html file
header('Content-Type: text/html');
header("Content-Disposition: attachment; filename=Order_Receipt_$voucherNumber.html");

// Start HTML content
echo "
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h1 { color: #4CAF50; text-align: center; }
        .receipt-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .receipt-table th, .receipt-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .receipt-table th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
    </style>
</head>
<body>

<h1>Order Receipt</h1>
<p>Thank you for your order! Your voucher number is: <strong>$voucherNumber</strong></p>

<table class='receipt-table'>
    <tr>
        <th>S.N</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>";

$serialNumber = 1;
foreach ($cartItems as $item) {
    $itemTotal = $item['price'] * $item['Quantity'];
    echo "
    <tr>
        <td>$serialNumber</td>
        <td>{$item['title']}</td>
        <td>Rs. " . number_format($item['price'], 2) . "</td>
        <td>{$item['Quantity']}</td>
        <td>Rs. " . number_format($itemTotal, 2) . "</td>
    </tr>";
    $serialNumber++;
}

echo "
    <tr>
        <td colspan='4' class='total'>Total Price</td>
        <td class='total'>Rs. " . number_format($totalPrice, 2) . "</td>
    </tr>
</table>

</body>
</html>";
?>
