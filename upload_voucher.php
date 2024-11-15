<?php
// Include database configuration
include('config/constants.php'); // Ensure constants.php has the database connection `$conn`

// Process the form submission for voucher upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['voucher_image'])) {
    $voucherImage = $_FILES['voucher_image'];
    $orderId = $_POST['order_id'];

    echo $orderId;

    // Debugging: Check if `order_id` is received
    if (empty($orderId)) {
        echo "Error: Order ID is missing.";
        exit();
    }
    echo "Debug: Received Order ID - $orderId<br>";

    // Allowed file types
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    if (!in_array($voucherImage['type'], $allowedFileTypes)) {
        echo "Invalid file type. Only JPG, PNG, GIF, or PDF files are allowed.";
        exit();
    }

    // Define the upload directory
    $uploadDirectory = 'images/uploads_vouchers/';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    // Sanitize the filename and set target path
    $voucherImageName = uniqid() . '_' . basename($voucherImage['name']);
    $targetFilePath = $uploadDirectory . $voucherImageName;

    // Attempt to move the uploaded file
    if (move_uploaded_file($voucherImage['tmp_name'], $targetFilePath)) {
        // Debugging: Check if `order_id` exists in the `checkout` table
        $stmt = $conn->prepare("SELECT order_id FROM checkout WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            echo "Error: Order ID not found in the database.";
            $stmt->close();
            exit();
        }
        $stmt->close();

        // Insert voucher upload record
        $stmt = $conn->prepare("INSERT INTO voucher_uploads (order_id, voucher_image) VALUES (?, ?)");
        $stmt->bind_param("is", $orderId, $voucherImageName);

        if ($stmt->execute()) {
            // Redirect to the order details page with specific order ID
            header("Location: order_details.php?order_id=" . $orderId);
            exit();
        } else {
            echo "Error saving voucher details in the database.";
        }
        $stmt->close();
    } else {
        echo "Error uploading the voucher image. Please try again.";
    }
}
?>
