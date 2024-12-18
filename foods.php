
    <?php include('partials-front/menu.php'); ?>

    

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center" style="background-image: 
        linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), /* Gradient overlay */
        url(./images/hero.png);">
        <div class="container">
            
            <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php 
                //Display Foods that are Active
                $sql = "SELECT * FROM food WHERE active='Yes'";

                //Execute the Query
                $res=mysqli_query($conn, $sql);

                //Count Rows
                $count = mysqli_num_rows($res);

                //CHeck whether the foods are availalable or not
                if($count>0)
                {
                    //Foods Available
                    while($row=mysqli_fetch_assoc($res))
                    {
                        //Get the Values
                        $id = $row['id'];
                        $title = $row['title'];
                        $cooking_time = $row['cooking_time'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $image_name = $row['image_name'];
                        ?>
                        
                        <div class="food-menu-box">
                            <form  action="manage_cart.php" method="post">
                            <div class="food-menu-img">
                                <?php 
                                    //CHeck whether image available or not
                                    if($image_name=="")
                                    {
                                        //Image not Available
                                        echo "<div class='error'>Image not Available.</div>";
                                    }
                                    else
                                    {
                                        //Image Available
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                                        <?php
                                    }
                                ?>
                                
                            </div>

                            <div class="food-menu-desc">
                            <div style="display: flex; justify-content: space-between;align-items:center;">
                                <h4><?php echo $title; ?></h4>
                                <div class="cook-time" style="padding: 8px 7px; background: orange ; border-radius: 5px; color:white;"><?php echo $cooking_time; ?> Minutes</div>
                            </div>
                                <p class="food-price">RS <?php echo $price; ?></p>
                                <p class="food-detail">
                                    <?php echo $description; ?>
                                </p>
                                <br>
                                <input type="hidden" value="<?php echo $id; ?>" name="id">
                                <input type="hidden" value="<?php echo $title; ?>" name="title">
                                <input type="hidden" value="<?php echo $price; ?>" name="price">
                                <input type="hidden" value="<?php echo $image_name ?>" name="image_name">
                                <input type="hidden" value="<?php echo $cooking_time ?>" name="cooking_time">
                                <button type="submit" name="Add_To_Cart"  class="btn btn-primary">Add To Cart</button>
                                
                                
                            </div>
                            </form>
                        </div>

                        <?php
                    }
                }
                else
                {
                    //Food not Available
                    echo "<div class='error'>Food not found.</div>";
                }
            ?>

            

            

            <div class="clearfix"></div>

            

        </div>

    </section>
    <!-- fOOD Menu Section Ends Here -->

    <?php include('partials-front/footer.php'); ?>