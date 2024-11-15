<?php include('partials-front/menu.php'); ?>

<?php
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
    $stmt = $conn->prepare("INSERT INTO food_requests (dish_name, picture, description, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("sss", $dish_name, $picture, $description);

    if ($stmt->execute()) {
        echo "<div class='alert success'>Request submitted successfully!</div>";
    } else {
        echo "<div class='alert error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
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
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container-box {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        button {
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            text-align: center;
        }

        .success {
            background-color: #28a745;
        }

        .error {
            background-color: #dc3545;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container-box">
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

</body>
</html>
