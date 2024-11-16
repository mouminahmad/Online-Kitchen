<?php include('partials/menu.php'); ?>

<div style="font-family: Arial, sans-serif; padding: 20px;">
    <div>
        <h1 style="text-align: center; color: #333;">Manage Category</h1>

        <br />

        <div style="margin-left: 80;">
        <!-- Button to Add Category -->
        <a href="<?php echo SITEURL; ?>admin/add-category.php" style="display: inline-block; padding: 6px 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; text-decoration: none; cursor: pointer;">Add Category</a>
        </div>

        <?php 
            if(isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
            if(isset($_SESSION['remove'])) {
                echo $_SESSION['remove'];
                unset($_SESSION['remove']);
            }
            if(isset($_SESSION['delete'])) {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
            if(isset($_SESSION['no-category-found'])) {
                echo $_SESSION['no-category-found'];
                unset($_SESSION['no-category-found']);
            }
            if(isset($_SESSION['update'])) {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
            if(isset($_SESSION['upload'])) {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
            if(isset($_SESSION['failed-remove'])) {
                echo $_SESSION['failed-remove'];
                unset($_SESSION['failed-remove']);
            }
        ?>

        <table style="width: 90%; margin: 20px auto; border-collapse: collapse; border: 1px solid #ddd;">
            <tr style="background-color: #f4f4f4;">
                <th style="padding: 8px; border: 1px solid #ddd;">S.N.</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Title</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Image</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Featured</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Active</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Update</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Delete</th>
            </tr>

            <?php 
                $sql = "SELECT * FROM tbl_category";
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);
                $sn = 1;

                if($count > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        $featured = $row['featured'];
                        $active = $row['active'];
            ?>

            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $sn++; ?>.</td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $title; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    <?php  
                        if($image_name == "") {
                            echo "<div style='color: red;'>Image not Added.</div>";
                        } else {
                    ?>
                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" style="width: 100px;">
                    <?php } ?>
                </td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $featured; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $active; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    <a href="<?php echo SITEURL; ?>admin/update-category.php?id=<?php echo $id; ?>">
                        <img src="../images/icons/update.png" style="width: 20px; cursor: pointer;">
                    </a>
                </td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    <a href="<?php echo SITEURL; ?>admin/delete-category.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>">
                        <img src="../images/icons/delete.png" style="width: 20px; cursor: pointer;">
                    </a>
                </td>
            </tr>

            <?php 
                    }
                } else {
                    echo "<tr> <td colspan='7' style='padding: 8px; border: 1px solid #ddd; color: red; text-align: center;'> Category not Added Yet. </td> </tr>";
                }
            ?>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
