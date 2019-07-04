<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Image</th>
            <th>Role</th>
            <th colspan="4">Action</th>
        </tr>
    </thead>


    <tbody>
        <?php
        global $connection;
        $query = "SELECT * FROM Users";
        $select_all_users = mysqli_query($connection, $query);

        if(!$select_all_users){
            die('Query Failed '.mysqli_error($connection));
        }
    
        while($row = mysqli_fetch_assoc($select_all_users)){
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_password = $row['password'];
            $user_first_name = $row['user_first_name'];
            $user_last_name = $row['user_last_name'];
            $user_email = $row['user_email'];
            $user_image = $row['user_image'];
            $user_role = $row['role'];

            echo "<tr>";
                echo "<td>$user_id</td>";
                echo "<td>$username</td>";
                echo "<td>$user_first_name</td>";
                echo "<td>$user_last_name</td>";
                echo "<td>$user_email</td>";
                echo "<td>$user_image</td>";
                echo "<td>$user_role</td>";

                echo "<td><a href='./users.php?change_to_admin={$user_id}'>Admin</a></td>";
                echo "<td><a href='./users.php?change_to_subscriber={$user_id}'>Subscriber</a></td>";
                echo "<td><a href='./users.php?source=edit_user&edit_user={$user_id}'>Edit</a></td>";
                echo "<td><a href='./users.php?delete={$user_id}'>Delete</a></td>";
            echo "</tr>";
        }
       
        ?>
    </tbody>
</table> 

<?php

if(isset($_GET['delete'])){
    //delete user
    if(isset($_SESSION['user_role'])){
        if($_SESSION['user_role']=='admin'){
            $user_id_signed_in = mysqli_real_escape_string($connection,$_GET['delete']);
            $delete_user_id = $_GET['delete'];
            $query = "DELETE FROM Users WHERE user_id = {$delete_user_id}";
            $result = mysqli_query($connection, $query);
            header("Location: users.php");
        }
    }
}

if(isset($_GET['change_to_subscriber'])){
    $unapproved_user_id = escape($_GET['change_to_subscriber']);
    $query = "UPDATE Users SET role = 'subscriber' WHERE user_id = $unapproved_user_id";
    $result = mysqli_query($connection, $query);
    header("Location: users.php");
}

if(isset($_GET['change_to_admin'])){
    $approved_user_id = escape($_GET['change_to_admin']);
    $query = "UPDATE Users SET role = 'admin' WHERE user_id = $approved_user_id";
    $result = mysqli_query($connection, $query);
    header("Location: users.php");
}

?>