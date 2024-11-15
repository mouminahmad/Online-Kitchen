<?php include('partials-front/menu.php'); ?>

<?php


// Check if user is logged in
if (!isset($_SESSION['u_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch the user data
$user_id = $_SESSION['u_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['customer_name'];
    $email = $_POST['customer_email'];
    $contact = $_POST['customer_contact'];
    $address = $_POST['customer_address'];

    // Update user data in the database
    $update_query = "UPDATE users SET customer_name = ?, customer_email = ?, customer_contact = ?, customer_address = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssisi', $name, $email, $contact, $address, $user_id);

    if ($update_stmt->execute()) {
        // Set a session message and redirect to avoid form resubmission
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
        exit;
    } else {
        $error_message = "Failed to update profile.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            
            height: 100vh;
        }

        .box{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Form container styling */
        .form-container {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            
        }

        /* Form title */
        .form-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
            text-align: center;
        }

        /* Success and error messages */
        .success-message {
            color: #28a745;
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
        }

        .error-message {
            color: #dc3545;
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Form labels */
        .form-container label {
            display: block;
            font-size: 14px;
            color: #555555;
            margin-bottom: 5px;
        }

        /* Form inputs */
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Textarea specific styling */
        .form-container textarea {
            resize: none;
            height: 100px;
        }

        /* Buttons */
        .form-container button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="box">
    <div class="form-container">
        <h1>Update Profile</h1>
        <?php if (isset($success_message)) echo "<p class='success-message'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error-message'>$error_message</p>"; ?>
        
        <form method="POST">
            <label for="customer_name">Name:</label>
            <input type="text" id="customer_name" name="customer_name" value="<?php echo $user['customer_name']; ?>" required>

            <label for="customer_email">Email:</label>
            <input type="email" id="customer_email" name="customer_email" value="<?php echo $user['customer_email']; ?>" required>

            <label for="customer_contact">Contact:</label>
            <input type="text" id="customer_contact" name="customer_contact" value="<?php echo $user['customer_contact']; ?>" required>

            <label for="customer_address">Address:</label>
            <textarea id="customer_address" name="customer_address" required><?php echo $user['customer_address']; ?></textarea>

            <button type="submit">Update Profile</button>
        </form>
    </div>
    </div>
</body>
</html>
<?php include('partials-front/footer.php'); ?>