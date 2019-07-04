<?php

if(isset($_POST['create_user'])){
    
    $user_first_name = escape($_POST['user_first_name']);
    $user_last_name = escape($_POST['user_last_name']);
    $username = escape($_POST['username']);
    $user_email = escape($_POST['user_email']);
    $password = escape($_POST['password']);
    $user_role = escape($_POST['user_role']);

    //$_FILES['post_image']['name'];
    //$post_image_temp = $_FILES['post_image']['tmp_name'];
    //move_uploaded_file($post_image, "../images/$post_image");

    $password = password_hash($password,PASSWORD_BCRYPT,array('cost'=>12));

    $query = "INSERT INTO Users(user_first_name,user_last_name,username,user_email,password,role) ";
    $query.="VALUES ('{$user_first_name}','{$user_last_name}','{$username}','{$user_email}','{$password}','{$user_role}')";

    $create_user = mysqli_query($connection, $query);

    if(!$create_user){
        confirm($create_user);
        
    } else{
        echo "<h5 class='text-success'>User created!<h5>";
        echo "<a href='users.php'>View users</a>";
        echo "<hr>";
    }
}

?>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="first_name">First name</label>
        <input type="text" class="form-control" name="user_first_name">
    </div>

    <div class="form-group">
        <label for="last_name">Last name</label>
        <input type="text" class="form-control" name="user_last_name">
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password">
    </div>



    <div class="form-group">
        <label for="user_ids">User IDs</label>
      
        <select style="padding-bottom:25px;" name="user_role" id="">
            <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option>
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
        <input class="btn btn-primary" type="submit" name="create_user" value="Create User">
    </div>

</form>
