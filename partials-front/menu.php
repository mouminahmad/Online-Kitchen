<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodhub</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
    <div class="container">
        <div class="logo">
            <a href="http://localhost/online-kitchen/" title="Logo">
                <img src="images/ok-logo.png" alt="Restaurant Logo" class="img-responsive">
            </a>
        </div>
        <br>
        <div class="menu text-right">
            <ul>
                <li>
                    <a href="<?php echo SITEURL; ?>">Home</a>
                </li>
                <li>
                    <a href="<?php echo SITEURL; ?>categories.php">Categories</a>
                </li>
                <li>
                    <a href="<?php echo SITEURL; ?>foods.php">Foods</a>
                </li>
                <?php
                $count = 0;
                if (isset($_SESSION['cart'])) {
                    $count = count($_SESSION['cart']);
                }
                echo '<li class="nav-item"><a href="mycart.php" class="nav-link active">My Cart (' . $count . ')</a></li>';
                ?>
                <li>
                    <?php
                    if (empty($_SESSION["u_id"])) {
                        echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>';
                    } else {
                        echo '<li class="nav-item"><a href="order_details.php" class="nav-link active">Myorders</a></li>';
                        echo '<li class="nav-item"><a href="user_request.php" class="nav-link active">Food Request</a></li>';
                        echo '<li class="nav-item"><a href="update_profile.php" class="nav-link active">Update Profile</a></li>';
                        echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a></li>';
                    }
                    ?>
                </li>
            </ul>
        </div>
        
        <div class="clearfix"></div>
    </div>
</section>

    <!-- Navbar Section Ends Here -->