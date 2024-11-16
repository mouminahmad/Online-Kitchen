<?php include('partials/menu.php'); ?>

<div style="font-family: Arial, sans-serif; padding: 20px;">
    <div>
        <h1 style="text-align: center; color: #333;">Manage Users</h1>

        <br />

        <!-- Displaying Session Messages -->
        <?php 
            if(isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
            if(isset($_SESSION['delete'])) {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
            if(isset($_SESSION['update'])) {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
            if(isset($_SESSION['user-not-found'])) {
                echo $_SESSION['user-not-found'];
                unset($_SESSION['user-not-found']);
            }
            if(isset($_SESSION['pwd-not-match'])) {
                echo $_SESSION['pwd-not-match'];
                unset($_SESSION['pwd-not-match']);
            }
            if(isset($_SESSION['change-pwd'])) {
                echo $_SESSION['change-pwd'];
                unset($_SESSION['change-pwd']);
            }
        ?>

        <div style="margin-left: 90px;">
        <!-- Button to Add User -->
        <a href="add-users.php" style="display: inline-block; padding: 6px 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; text-decoration: none; cursor: pointer;">Add User</a>

        </div>

        <table style="width: 90%; margin: 20px auto; border-collapse: collapse; border: 1px solid #ddd;">
            <tr style="background-color: #f4f4f4;">
                <th style="padding: 8px; border: 1px solid #ddd;">S.N.</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Full Name</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Username</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Email</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Contact</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Address</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Created At</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Update User</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Delete User</th>
            </tr>

            <?php 
                // Query to Get all Users
                $sql = "SELECT * FROM users";
                $res = mysqli_query($conn, $sql);

                if($res == TRUE) {
                    $count = mysqli_num_rows($res);
                    $sn = 1;

                    if($count > 0) {
                        while($rows = mysqli_fetch_assoc($res)) {
                            $id = $rows['id'];
                            $full_name = $rows['customer_name'];
                            $username = $rows['username'];
                            $email = $rows['customer_email'];
                            $contact = $rows['customer_contact'];
                            $address = $rows['customer_address'];
                            $created = $rows['created_at'];
            ?>

            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $sn++; ?>.</td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $full_name; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $username; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $email; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $contact; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $address; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;"><?php echo $created; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    <a href="<?php echo SITEURL; ?>admin/update-users.php?id=<?php echo $id; ?>">
                        <img src="../images/icons/update-user.png" style="width: 20px; cursor: pointer;">
                    </a>
                </td>
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    <a href="<?php echo SITEURL; ?>admin/delete-users.php?id=<?php echo $id; ?>">
                        <img src="../images/icons/delete-user.png" style="width: 20px; cursor: pointer;">
                    </a>
                </td>
            </tr>

            <?php
                        }
                    } else {
                        echo "<tr><td colspan='9' style='padding: 8px; border: 1px solid #ddd; color: red; text-align: center;'>No Users Added Yet.</td></tr>";
                    }
                }
            ?>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
