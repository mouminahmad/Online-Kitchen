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

// Add shipping charge of RS 250
$shipping_charge = 250;
$grand_total = $total + $shipping_charge;
?>

<div class="main-content" style="background-color: #f7f7f7; padding: 40px 0;">
    <div class="wrapper" style="width: 90%; margin: 0 auto; background-color: #fff; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h1 class="text-center" style="color: #333; font-size: 2em; margin-bottom: 20px;">Checkout</h1>

        <form action="process_order.php" method="POST" style="display: flex; flex-wrap: wrap; gap: 20px;">

            <!-- Shipping Information Column -->
            <div style="flex: 1; min-width: 300px; padding: 20px; border-right: 1px solid #ddd; background-color: #fafafa; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);">
                <h2 style="font-size: 1.5em; color: #333; margin-bottom: 20px;">Shipping Information</h2>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="shipping_name" style="font-weight: bold; color: #555;">Full Name:</label>
                    <input type="text" id="shipping_name" name="shipping_name" required 
                           style="width: 100%; padding: 15px; font-size: 1em; border: 2px solid #ddd; border-radius: 8px; margin-top: 8px; box-sizing: border-box; background-color: #f9f9f9;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="shipping_address" style="font-weight: bold; color: #555;">Shipping Address:</label>
                    <input type="text" id="shipping_address" name="shipping_address" required 
                           style="width: 100%; padding: 15px; font-size: 1em; border: 2px solid #ddd; border-radius: 8px; margin-top: 8px; box-sizing: border-box; background-color: #f9f9f9;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="shipping_phone" style="font-weight: bold; color: #555;">Phone Number:</label>
                    <input type="text" id="shipping_phone" name="shipping_phone" required 
                           title="Please enter a valid phone number (10 digits)." 
                           style="width: 100%; padding: 15px; font-size: 1em; border: 2px solid #ddd; border-radius: 8px; margin-top: 8px; box-sizing: border-box; background-color: #f9f9f9;">
                </div>
            </div>

            <!-- Order Summary Column (Styled as Card) -->
            <div style="flex: 1; min-width: 300px; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h2 style="font-size: 1.5em; color: #333; margin-bottom: 20px;">Order Summary</h2>

                <!-- Order Details -->
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

                <!-- Price Details -->
                <div style="padding: 10px 0; border-top: 1px solid #ddd; margin-top: 20px;">
                    <p style="font-size: 1.2em; color: #333; margin-bottom: 10px;">Total Price: <strong>RS <?php echo number_format($total, 2); ?></strong></p>
                    <p style="font-size: 1.2em; color: #333; margin-bottom: 10px;">Shipping Charge: <strong>RS <?php echo number_format($shipping_charge, 2); ?></strong></p>
                    <p style="font-size: 1.5em; color: #4CAF50; margin-bottom: 20px;">Grand Total: <strong>RS <?php echo number_format($grand_total, 2); ?></strong></p>
                </div>

                <!-- Confirm Order Button -->
                <button type="submit" class="btn-checkout" style="text-decoration: none; color: #fff; background-color: #28a745; padding: 12px 24px; border-radius: 8px; font-size: 1.1em; display: block; width: 100%; border: none; cursor: pointer;">
                    Confirm Order
                </button>
            </div>

        </form>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>
