<?php include('partials-front/menu.php'); ?>

<?php


// Check if the user is logged in
if (!isset($_SESSION['u_id'])) {
    echo "You need to log in to view your requests.";
    exit();
}

$user_id = $_SESSION['u_id']; // Assuming user ID is stored in session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dish_name = $_POST['dish_name'];
    $description = $_POST['description'];

    // File Upload Handling
    $picture = '';
    if (!empty($_FILES['picture']['name'])) {
        $target_dir = "images/custome_food/";
        $file_name = uniqid("dish_", true) . '.' . strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . $file_name;

        // Move the uploaded file
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
            $picture = $file_name; // Save only the file name to the database
        } else {
            echo "Failed to upload the file.";
        }
    }

    // Insert into Database
    $stmt = $conn->prepare("INSERT INTO food_requests (user_id, dish_name, picture, description, status, created_at) VALUES (?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("ssss", $user_id, $dish_name, $picture, $description);

    if ($stmt->execute()) {
        // echo "<div class='alert success'>Request submitted successfully!</div>";
    } else {
        echo "<div class='alert error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Food Dish</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Add your CSS styling here */
        body {
            font-family: 'Roboto', sans-serif;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
        }
        input[type="text"], input[type="file"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            text-align: left;
            background-color: #f4f4f4;
        }
        td img {
            max-width: 100px;
            height: auto;
        }
        .alert {
            padding: 15px;
            margin-top: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #4CAF50;
            color: white;
        }
        .error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Request a Food Dish</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="dish_name">Dish Name:</label>
                <input type="text" name="dish_name" id="dish_name" required>
            </div>

            <div class="form-group">
                <label for="picture">Picture (optional):</label>
                <input type="file" name="picture" accept=".jpg, .jpeg, .png, .gif">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <button type="submit">Submit Request</button>
        </form>
    </div>

    <div class="container">
        <h1>Your Food Requests</h1>
        <?php
        // Fetch the user's food requests
        $query = "SELECT * FROM food_requests WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Dish Name</th>
                        <th>Picture</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['dish_name'] . "</td>
                        <td>";
                if (!empty($row['picture'])) {
                    echo "<img src='images/custome_food/" . $row['picture'] . "'>";
                } else {
                    echo "No Picture";
                }
                echo "</td>
                        <td>" . $row['description'] . "</td>
                        <td>" . $row['status'] . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You have not made any food requests yet.</p>";
        }
        $stmt->close();
        ?>
    </div>

</body>
</html>
