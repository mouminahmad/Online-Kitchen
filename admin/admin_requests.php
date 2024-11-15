<?php include('partials/menu.php'); ?>

<?php
// Fetch Requests
$sql = "SELECT * FROM food_requests";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['feedback'])) {
        $id = $_POST['id'];
        $feedback = $_POST['feedback'];

        // Update Feedback and Status
        $update_sql = "UPDATE food_requests SET user_feedback='$feedback', status='Reviewed' WHERE id=$id";
        if ($conn->query($update_sql) === TRUE) {
            echo "<div class='alert success'>Feedback updated successfully.</div>";
            // Re-fetch updated data
            $result = $conn->query($sql); // Re-fetch the data after update
        } else {
            echo "<div class='alert error'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['delete_id'])) {
        // Delete Request
        $delete_id = $_POST['delete_id'];
        $delete_sql = "DELETE FROM food_requests WHERE id=$delete_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "<div class='alert success'>Request deleted successfully.</div>";
            // Re-fetch updated data after deletion
            $result = $conn->query($sql); // Re-fetch the data after delete
        } else {
            echo "<div class='alert error'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['status']) && isset($_POST['status_id'])) {
        // Update Status
        $status = $_POST['status'];
        $status_id = $_POST['status_id'];
        
        // Update the status of the food request
        $update_status_sql = "UPDATE food_requests SET status='$status' WHERE id=$status_id";
        if ($conn->query($update_status_sql) === TRUE) {
            echo "<div class='alert success'>Status updated successfully.</div>";
            // Re-fetch updated data after status update
            $result = $conn->query($sql); // Re-fetch the data after update
        } else {
            echo "<div class='alert error'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: left;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #fff;
        }

        .success {
            background-color: #28a745;
        }

        .error {
            background-color: #f44336;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }

        .action-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .status-dropdown {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        .delete-button {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #d32f2f;
        }

        .feedback-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Food Requests</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Dish Name</th>
                <th>Picture</th>
                <th>Description</th>
                <th>Status</th>
                <th>Feedback</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['dish_name']; ?></td>
                <td>
                    <?php if (!empty($row['picture'])) { ?>
                        <img src="../images/custome_food/<?php echo $row['picture']; ?>" alt="<?php echo $row['dish_name']; ?>">
                    <?php } else { echo "No Picture"; } ?>
                </td>
                <td><?php echo $row['description']; ?></td>
                <td>
                    <!-- Dropdown to select status -->
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="status_id" value="<?php echo $row['id']; ?>">
                        <select name="status" class="status-dropdown">
                            <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Reviewed" <?php echo $row['status'] == 'Reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                            <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                </td>
                <td>
                    <!-- Feedback Form -->
                    <div class="feedback-container">
                        <strong>Current Feedback:</strong>
                        <p><?php echo $row['user_feedback'] ?: 'No feedback provided yet.'; ?></p>
                        <form action="" method="POST" class="action-form">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <textarea name="feedback" placeholder="Update feedback..." required><?php echo $row['user_feedback']; ?></textarea>
                            <button type="submit">Update Feedback</button>
                        </form>
                    </div>
                </td>
                <td>
                    <!-- Delete Request -->
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
