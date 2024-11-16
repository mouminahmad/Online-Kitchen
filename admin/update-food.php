<?php
ob_start(); // Start output buffering
include('partials/menu.php');

// Check whether ID is set or not
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL Query to Get the Selected Food
    $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
    $res2 = mysqli_query($conn, $sql2);

    if ($res2 && mysqli_num_rows($res2) > 0) {
        // Get the values based on query executed
        $row2 = mysqli_fetch_assoc($res2);

        // Extract values
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $active = $row2['active'];
    } else {
        // Redirect if no food is found
        $_SESSION['no-food-found'] = "<div class='error'>Food not found.</div>";
        header('location:' . SITEURL . 'admin/manage-food.php');
        exit;
    }
} else {
    // Redirect to Manage Food if no ID is set
    header('location:' . SITEURL . 'admin/manage-food.php');
    exit;
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>"></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" cols="30" rows="5"><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><input type="number" name="price" value="<?php echo $price; ?>"></td>
                </tr>
                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($current_image == "") { ?>
                            <div class="error">Image not available.</div>
                        <?php } else { ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Select New Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">
                            <?php
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            $res = mysqli_query($conn, $sql);
                            if ($res && mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $category_id = $row['id'];
                                    $category_title = $row['title'];
                                    ?>
                                    <option value="<?php echo $category_id; ?>" <?php if ($current_category == $category_id) echo "selected"; ?>>
                                        <?php echo htmlspecialchars($category_title); ?>
                                    </option>
                                    <?php
                                }
                            } else {
                                echo "<option value='0'>Category Not Available.</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?php if ($featured == "Yes") echo "checked"; ?>> Yes
                        <input type="radio" name="featured" value="No" <?php if ($featured == "No") echo "checked"; ?>> No
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if ($active == "Yes") echo "checked"; ?>> Yes
                        <input type="radio" name="active" value="No" <?php if ($active == "No") echo "checked"; ?>> No
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $current_image = $_POST['current_image'];
            $category = $_POST['category'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $image_name = $_FILES['image']['name'];
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_name = "Food-Name-" . rand(0000, 9999) . ".$ext";
                $src_path = $_FILES['image']['tmp_name'];
                $dest_path = "../images/food/" . $image_name;

                if (!move_uploaded_file($src_path, $dest_path)) {
                    $_SESSION['upload'] = "<div class='error'>Failed to upload new image.</div>";
                    header('location:' . SITEURL . 'admin/manage-food.php');
                    exit;
                }

                if ($current_image != "") {
                    $remove_path = "../images/food/" . $current_image;
                    if (!unlink($remove_path)) {
                        $_SESSION['remove-failed'] = "<div class='error'>Failed to remove current image.</div>";
                        header('location:' . SITEURL . 'admin/manage-food.php');
                        exit;
                    }
                }
            } else {
                $image_name = $current_image;
            }

            $sql3 = "UPDATE tbl_food SET 
                title = '$title',
                description = '$description',
                price = $price,
                image_name = '$image_name',
                category_id = '$category',
                featured = '$featured',
                active = '$active'
                WHERE id=$id";

            $res3 = mysqli_query($conn, $sql3);

            if ($res3) {
                $_SESSION['update'] = "<div class='success'>Food updated successfully.</div>";
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to update food.</div>";
            }

            header('location:' . SITEURL . 'admin/manage-food.php');
            exit;
        }
        ?>
    </div>
</div>

<?php
include('partials/footer.php');
ob_end_flush(); // End output buffering
?>
