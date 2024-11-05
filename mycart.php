<?php

// Initialize the cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<?php include('partials-front/menu.php'); ?>

<div class="main-content" style="background-color: #f7f7f7; padding: 40px 0;">
    <div class="wrapper" style="width: 90%; margin: 0 auto; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px;">
        <h1 class="text-center" style="color: #333; font-size: 2em; margin-bottom: 20px; font-weight: bold;">Order Details</h1>

        <div style="display: flex; justify-content: space-between; gap: 40px;">
            <!-- First Column: Cart Items -->
            <div style="flex: 0 0 65%;">
                <form action="manage_cart.php" method="POST">
                    <table class="content-table" style="width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: left;">
                        <thead>
                            <tr style="background-color: #4CAF50; color: white; font-size: 1.1em; text-transform: uppercase;">
                                <th style="padding: 8px;">S.N</th>
                                <th style="padding: 8px;">Image</th>
                                <th style="padding: 8px;">Name</th>
                                <th style="padding: 8px;">Price</th>
                                <th style="padding: 8px;">Quantity</th>
                                <th style="padding: 8px;">Total</th>
                                <th style="padding: 8px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if (isset($_SESSION['cart'])) {
                                $serialNumber = 1; // Initialize serial number
                                foreach ($_SESSION['cart'] as $key => $value) {
                                    $total += $value['price'] * $value['Quantity']; // Calculate total with quantity
                                    $image_name = $value['image_name']; // Get image name from the cart item
                                    $itemTotal = $value['price'] * $value['Quantity']; // Item total

                                    echo "
                                    <tr style='border-bottom: 1px solid #ddd;'>
                                        <td style='padding: 8px; font-size: 1em;'>$serialNumber</td>
                                        <td style='padding: 8px;'><img src='" . SITEURL . "images/food/$image_name' alt='{$value['title']}' class='img-responsive img-curve' width='80' height='80' style='object-fit: cover;'></td>
                                        <td style='padding: 8px; font-size: 1em;'>{$value['title']}</td>
                                        <td style='padding: 8px; font-size: 1em;'>RS " . number_format($value['price'], 2) . "</td>
                                        <td style='padding: 8px;'>
                                            <input type='number' name='quantity[$key]' style='text-align: center; padding: 5px; width: 50px; border: 1px solid #ddd; border-radius: 5px;' value='{$value['Quantity']}' min='1' max='10'>
                                        </td>
                                        <td style='padding: 8px;'>RS " . number_format($itemTotal, 2) . "</td>
                                        <td style='padding: 8px;'>
                                            <button type='submit' name='Remove_Item' class='btn-remove' style='color: #fff; background-color: #ff4d4d; padding: 5px 10px; border: none; outline: none; border-radius: 5px; text-decoration: none; cursor: pointer;'>Remove</button>
                                            <input type='hidden' name='Item_id' value='$value[id]'>
                                        </td>
                                    </tr>";

                                    $serialNumber++; // Increment serial number
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>

            <!-- Second Column: Payment Info -->
            <div style="flex: 0 0 28%; padding: 20px; background-color: #f2f2f2; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); height: 280px;">
                <h3 style="font-size: 1.6em; color: #333;">Payment Information</h3>
                <hr>
                <div style="font-size: 1.2em; color: #333; padding: 10px 0;">
                    <strong>Total Price:</strong> RS <?php echo number_format($total, 2); ?>
                </div>
                <div style="font-size: 1.2em; color: #333; padding: 10px 0;">
                    <strong>Tax (5%):</strong> RS <?php echo number_format($total * 0.05, 2); ?>
                </div>
                <div style="font-size: 1.2em; color: #333; padding: 10px 0; font-weight: bold;">
                    <strong>Grand Total:</strong> RS <?php echo number_format($total + ($total * 0.05), 2); ?>
                </div>

                <!-- Checkout Button -->
                <form action="checkout.php" method="post">
                    <!-- Cash on Delivery Option -->
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 1.2em; color: #333;">Payment Method</h3>

                        <!-- Cash on Delivery Radio Button -->
                        <label style="font-size: 1.1em; margin-right: 20px;">
                            <input type="radio" name="payment_method" value="cash_on_delivery" style="margin-right: 10px;" checked>
                            Cash on Delivery
                        </label>
                    </div>

                    <!-- Proceed to Checkout Button -->
                    <button class="btn-checkout"
                        style="text-decoration: none; color: #fff; background-color: #28a745; padding: 12px 24px; border-radius: 5px; font-size: 1.1em; transition: background-color 0.3s ease, transform 0.3s ease; width: 90%; display: block; text-align: center;">
                        Proceed to Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include('partials-front/footer.php'); ?>