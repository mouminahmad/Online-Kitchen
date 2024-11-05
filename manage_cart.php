<?php

session_start();
// Initialize the cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add to Cart Logic
    if (isset($_POST['Add_To_Cart'])) {
        $myitems = array_column($_SESSION['cart'], 'title');

        if (in_array($_POST['title'], $myitems)) {
            echo "<script>
            alert('Item Already Added');
            window.location.href = 'foods.php';
            </script>";
        } else {
            // Add the item to the cart
            $_SESSION['cart'][] = array(
                'id' => $_POST['id'],
                'title' => $_POST['title'],
                'price' => $_POST['price'],
                'image_name' => $_POST['image_name'],
                'Quantity' => 1
            );
            echo "<script>
            alert('Item Added');
            window.location.href = 'foods.php';
            </script>";
        }
    }

    // Remove Item Logic
    if (isset($_POST['Remove_Item'])) {
        $itemId = $_POST['Item_id']; // Assuming 'item_id' is the unique identifier for the item

        // // Remove the item from the cart based on its ID
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['id'] == $itemId) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                echo "<script>
            alert('Item Remove');
            window.location.href = 'mycart.php';
            </script>";
            }
        }
    }
}
