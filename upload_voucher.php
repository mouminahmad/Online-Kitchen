<?php
// Start the session
session_start();

// Check if the voucher number is set in the session
if (!isset($_SESSION['voucher_number'])) {
    header("Location: checkout.php"); // Redirect if no voucher exists
    exit();
}

include('config/constants.php'); // Database connection file

// Process the form submission for voucher upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['voucher_image'])) {
    $voucherNumber = $_SESSION['voucher_number'];
    $voucherImage = $_FILES['voucher_image'];

    // Verify the uploaded file is an image
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($voucherImage['type'], $allowedFileTypes)) {
        echo "Invalid file type. Only JPG, PNG, or GIF images are allowed.";
        exit();
    }

    // Define the upload directory
    $uploadDirectory = 'images/uploads_vouchers/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $voucherImageName = $voucherNumber . '_' . basename($voucherImage['name']);
    $targetFilePath = $uploadDirectory . $voucherImageName;

    // Move the uploaded file
    if (move_uploaded_file($voucherImage['tmp_name'], $targetFilePath)) {
        // Retrieve the `order_id` from the `checkout` table using `voucher_number`
        $stmt = $conn->prepare("SELECT order_id FROM checkout WHERE voucher_number = ?");
        $stmt->bind_param("s", $voucherNumber);
        $stmt->execute();
        $stmt->bind_result($orderId);
        $stmt->fetch();
        $stmt->close();

        // If an order is found, insert the voucher upload record
        if ($orderId) {
            $stmt = $conn->prepare("INSERT INTO voucher_uploads (order_id, voucher_image, voucher_status) VALUES (?, ?, 'Pending')");
            $stmt->bind_param("is", $orderId, $voucherImageName);

            if ($stmt->execute()) {
                // Clear the cart session after order completion
                unset($_SESSION['cart']); // This will reset the cart session

                // Redirect to order details page with the specific order ID
                header("Location: order_details.php?order_id=" . $orderId);
                exit();
            } else {
                echo "Error saving voucher details in the database.";
            }
            $stmt->close();
        } else {
            echo "Order not found for the provided voucher number.";
        }
    } else {
        echo "Error uploading the voucher image. Please try again.";
    }
}
?>
