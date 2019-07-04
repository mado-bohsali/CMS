<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>
        
        <?php
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $query = "SELECT * FROM Users WHERE username = '{$username}'";

            $select_user_by_username = mysqli_query($connection, $query);
            while($row = mysqli_fetch_array($select_user_by_username)){
                $user_id = $row['user_id'];
                $username = $row['username'];
                $user_password = $row['password'];
                $user_first_name = $row['user_first_name'];
                $user_last_name = $row['user_last_name'];
                $user_email = $row['user_email'];
                $user_image = $row['user_image'];
                $user_role = $row['role'];
            }
        }

        if(isset($_POST['edit_user'])){
            
            $new_user_first_name = $_POST['user_first_name'];
            $new_user_last_name = $_POST['user_last_name'];
            $new_username = $_POST['username'];
            $new_user_email = $_POST['user_email'];
            $new_password = $_POST['password'];
            $new_user_role = $_POST['user_role'];

            /** Change image of a user
            //$_FILES['post_image']['name'];
            //$post_image_temp = $_FILES['post_image']['tmp_name'];
            //move_uploaded_file($post_image, "../images/$post_image");

            // $query = "INSERT INTO Users(user_first_name,user_last_name,username,user_email,password,role) ";
            // $query.="VALUES ('{$user_first_name}','{$user_last_name}','{$username}','{$user_email}','{$password}','{$user_role}')";

            // $create_user = mysqli_query($connection, $query);

            // if(!$create_user){
            //     confirm($create_user);
            //     echo "User created into DB!<br>";
            // } else{

            // }
            **/

            $query = "UPDATE Users SET ";
            $query.= "user_first_name = '{$new_user_first_name}', ";
            $query.= "user_last_name = '{$new_user_last_name}', ";
            $query.= "username = '{$new_username}', ";
            $query.= "user_email = '{$new_user_email}', ";
            $query.= "password = '{$new_password}', ";
            $query.= "role = '{$new_user_role}' ";
            $query.= "WHERE username = '{$username}'";

            $update_user = mysqli_query($connection, $query);
            confirm($update_user);

            //header("Location: profile.php");
        }

        ?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Welcome to admin! <small>Author</small></h1>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="first_name">First name</label>
                                <input type="text" value="<?php echo $user_first_name; ?>" class="form-control" name="user_first_name">
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last name</label>
                                <input type="text" value="<?php echo $user_last_name; ?>" class="form-control" name="user_last_name">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" value="<?php echo $username; ?>" class="form-control" name="username">
                            </div>

                            <div class="form-group">
                                <label for="user_ids">User Role</label>
                            
                                <select style="padding-bottom:25px;" name="user_role" id="">
                                    
                                    <!--display a default option-->
                                    <option value='user_role'><?php echo $user_role; ?></option>
                                    <?php
                                        if($user_role=='admin'){
                                            echo "<option value='subscriber'>subscriber</option>";
                                        } else{
                                            echo "<option value='admin'>admin</option>";
                                        }
                                    ?>
                                    
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" value="<?php echo $user_password; ?>"class="form-control" name="password">
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="edit_user" value="Update my profile">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>
<?php include "includes/admin_footer.php"; ?>