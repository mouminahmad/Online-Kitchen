<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Store the intended action for redirection after login
    $_SESSION['redirect_after_login'] = 'checkout.php';
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Check if the cart is set or not
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: mycart.php'); // Redirect if cart is empty
    exit;
}

include('partials-front/menu.php');

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['Quantity'];
}
?>

<div class="main-content" style="background-color: #f7f7f7; padding: 40px 0;">
    <div class="wrapper" style="width: 90%; margin: 0 auto; background-color: #fff; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h1 class="text-center" style="color: #333; font-size: 2em;">Checkout</h1>

        <form action="process_order.php" method="POST">
            <div style="margin-bottom: 20px;">
                <h2 style="font-size: 1.5em;">Shipping Information</h2>
                <label for="shipping_name">Full Name:</label>
                <input type="text" id="shipping_name" name="shipping_name" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px;">
                
                <label for="shipping_address">Shipping Address:</label>
                <input type="text" id="shipping_address" name="shipping_address" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px;">
                
                <label for="shipping_phone">Phone Number:</label>
                <input type="text" id="shipping_phone" name="shipping_phone" required title="Please enter a valid phone number (10 digits)." style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <h2 style="font-size: 1.5em;">Order Summary</h2>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: left;">
                <thead>
                    <tr style="background-color: #4CAF50; color: white; font-size: 1.1em;">
                        <th style="padding: 8px;">S.N</th>
                        <th style="padding: 8px;">Image</th>
                        <th style="padding: 8px;">Name</th>
                        <th style="padding: 8px;">Price</th>
                        <th style="padding: 8px;">Quantity</th>
                        <th style="padding: 8px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $serialNumber = 1;
                    foreach ($_SESSION['cart'] as $item) {
                        $itemTotal = $item['price'] * $item['Quantity'];
                        echo "
                        <tr>
                            <td style='padding: 8px;'>$serialNumber</td>
                            <td style='padding: 8px;'><img src='" . SITEURL . "images/food/{$item['image_name']}' alt='{$item['title']}' width='80' height='80'></td>
                            <td style='padding: 8px;'>{$item['title']}</td>
                            <td style='padding: 8px;'>RS " . number_format($item['price'], 2) . "</td>
                            <td style='padding: 8px;'>{$item['Quantity']}</td>
                            <td style='padding: 8px;'>RS " . number_format($itemTotal, 2) . "</td>
                        </tr>";
                        $serialNumber++;
                    }
                    ?>
                </tbody>
            </table>

            <h3 style="font-size: 1.5em;">Total Price: RS <?php echo number_format($total, 2); ?></h3>

            <button type="submit" class="btn-checkout" style="text-decoration: none; color: #fff; background-color: #28a745; padding: 12px 24px; border-radius: 5px; font-size: 1.1em; display: block; margin: 20px auto;">
                Confirm Order
            </button>
        </form>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>
