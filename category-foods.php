    
    <?php include('partials-front/menu.php'); ?>

    <?php 
        //CHeck whether id is passed or not
        if(isset($_GET['category_id']))
        {
            //Category id is set and get the id
            $category_id = $_GET['category_id'];
            // Get the CAtegory Title Based on Category ID
            $sql = "SELECT title FROM category WHERE id=$category_id";

            //Execute the Query
            $res = mysqli_query($conn, $sql);

            //Get the value from Database
            $row = mysqli_fetch_assoc($res);
            //Get the TItle
            $category_title = $row['title'];
        }
        else
        {
            //CAtegory not passed
            //Redirect to Home page
            header('location:'.SITEURL);
        }
    ?>


    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center" style="background-image: 
        linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), /* Gradient overlay */
        url(./images/hero.png);">
        <div class="container">
            
            <h2>Foods on <a href="#" class="text-white">"<?php echo $category_title; ?>"</a></h2>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php 
            
               // Create SQL Query to Get foods based on Selected Category
$sql2 = "SELECT id, title, price, cooking_time, description, image_name FROM food WHERE category_id=$category_id";

// Execute the Query
$res2 = mysqli_query($conn, $sql2);

// Count the Rows
$count2 = mysqli_num_rows($res2);

// Check whether food is available or not
if ($count2 > 0) {
    // Food is Available
    while ($row2 = mysqli_fetch_assoc($res2)) {
        $id = $row2['id'];
        $title = $row2['title'];
        $price = $row2['price'];
        $cooking_time = $row2['cooking_time']; // Corrected here
        $description = $row2['description'];
        $image_name = $row2['image_name'];
        ?>
        
        <div class="food-menu-box">
            <div class="food-menu-img">
                <?php 
                if ($image_name == "") {
                    // Image not Available
                    echo "<div class='error'>Image not Available.</div>";
                } else {
                    // Image Available
                    ?>
                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                    <?php
                }
                ?>
            </div>

            <div class="food-menu-desc">
                <form action="manage_cart.php" method="post">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4><?php echo $title; ?></h4>
                        <div class="cook-time" style="padding: 8px 7px; background: orange; border-radius: 5px; color: white;"><?php echo $cooking_time; ?> Minutes</div>
                    </div>
                    <p class="food-price">RS <?php echo $price; ?></p>
                    <p class="food-detail">
                        <?php echo $description; ?>
                    </p>
                    <br>

                    <input type="hidden" value="<?php echo $id; ?>" name="id">
                    <input type="hidden" value="<?php echo $title; ?>" name="title">
                    <input type="hidden" value="<?php echo $price; ?>" name="price">
                    <input type="hidden" value="<?php echo $image_name; ?>" name="image_name">
                    <input type="hidden" value="<?php echo $cooking_time; ?>" name="cooking_time">
                    <button type="submit" name="Add_To_Cart" class="btn btn-primary">Add To Cart</button>
                </form>
            </div>
        </div>

        <?php
    }
} else {
    // Food not available
    echo "<div class='error'>Food not Available.</div>";
}
            
            ?>

            

            <div class="clearfix"></div>

            

        </div>

    </section>
    <!-- fOOD Menu Section Ends Here -->

    <?php include('partials-front/footer.php'); ?>