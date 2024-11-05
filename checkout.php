<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // User is logged in, display a message
    echo "User is logged in.";
} else {
    // User is not logged in
    // Store the intended action for redirection after login
    $_SESSION['redirect_after_login'] = 'mycart.php';
    // Redirect to login page
    header("Location: login.php");
    exit();
}
?>
