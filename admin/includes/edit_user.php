<?php
if(isset($_GET['edit_user'])){
    $selected_user_id = escape($_GET['edit_user']);

    global $connection;
    $query = "SELECT * FROM Users WHERE user_id = {$selected_user_id}";
    $select_user_by_id = mysqli_query($connection, $query);

    if(!$select_user_by_id){
        die('Query Failed '.mysqli_error($connection));
    }

    while($row = mysqli_fetch_assoc($select_user_by_id)){
        $user_first_name = $row['user_first_name'];
        $user_last_name = $row['user_last_name'];
        $username = $row['username'];
        $user_email = $row['user_email'];
        $password = $row['password'];
        $user_role = $row['role'];
    }

}

if(isset($_POST['edit'])){
    
    $user_first_name = escape($_POST['user_first_name']);
    $user_last_name = escape($_POST['user_last_name']);
    $username = escape($_POST['username']);
    $user_email = escape($_POST['user_email']);
    $password = escape($_POST['password']);
    $user_role = escape($_POST['user_role']);

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

    //Hash the password while updating user profile
    if(!empty($password)){
        $get_password = "SELECT password FROM Users WHERE user_id = $selected_user_id";
        $user_info = mysqli_query($connection, $get_password);
        confirm($user_info);

        $result = mysqli_fetch_array($user_info);
        $db_user_password = $result['password'];

        if($db_user_password != $password){ //already hashed
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost'=>12));
        }
    }

    
    $query = "UPDATE Users SET ";
    $query.= "user_first_name = '{$user_first_name}', ";
    $query.= "user_last_name = '{$user_last_name}', ";
    $query.= "username = '{$username}', ";
    $query.= "user_email = '{$user_email}', ";
    $query.= "password = '{$hashed_password}', ";
    $query.= "role = '{$user_role}' ";
    $query.= "WHERE user_id = {$selected_user_id}";

    $update_user = mysqli_query($connection, $query);
    confirm($update_user);

    echo "<h6 class='text-success text-center'>"."Profile updated!"."</h6>";
}
?>
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
        <label for="email">Email address</label>
        <input type="email" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input autocomplete="off" type="password" class="form-control" name="password">
    </div>



    <div class="form-group">
        <label for="user_ids">User Role</label>
      
        <select style="padding-bottom:25px;" name="user_role" id="">
            
            <!--display a default option-->
            <option value="<?php $user_role; ?>"> <?php echo $user_role; ?> </option>
            
            <?php
                if($user_role=='admin'){
                    echo "<option value='subscriber'>subscriber</option>";
                } else{
                    echo "<option value='admin'>admin</option>";
                }
            ?>
            
            <?php
            // $query = "SELECT * FROM Users";
            // $select_all_users = mysqli_query($connection, $query);
            // confirm($select_all_users);

            // while($row = mysqli_fetch_assoc($select_all_users)){
            //     $user_id = $row['user_id'];
            //     $user_role = $row['role'];
            //     echo "<option value='$user_id'>$user_role</option>"; //value and name hrefer to the same entity
            // }
            ?>
        </select>
    </div>

    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" class="form-control" name="post_image"> <!--saves in a temporary location -->
    <!--</div> -->

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit" value="Edit User">
    </div>

</form>