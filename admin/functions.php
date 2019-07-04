<?php
/* Some functions can be wrapped in helper functions */

function escape($input){ //invoke it upon every GET request
    global $connection;

    //Strip whitespace (or other characters) from the beginning and end of a string
    //and escapes special characters
    return mysqli_real_escape_string($connection, trim($input));
}

function checkStatusOrRole($table, $column_name, $status){
    global $connection;

    $query = "SELECT * FROM $table WHERE $column_name = '$status'";
    $selected_fields = mysqli_query($connection, $query);
    confirm($selected_fields);

    return mysqli_num_rows($selected_fields);
}

function login_user($username, $password){
     global $connection;

     $username = trim($username);
     $password = trim($password);

     $username = mysqli_real_escape_string($connection, $username);
     $password = mysqli_real_escape_string($connection, $password);


     $query = "SELECT * FROM Users WHERE username = '{$username}' ";
     $select_user_query = mysqli_query($connection, $query);
     if (!$select_user_query) {

         die("QUERY FAILED" . mysqli_error($connection));

     }


     while ($row = mysqli_fetch_array($select_user_query)) {

         $db_user_id = $row['user_id'];
         $db_username = $row['username'];
         $db_user_password = $row['password'];
         $db_user_firstname = $row['user_first_name'];
         $db_user_lastname = $row['user_last_name'];
         $db_user_role = $row['role'];


         if (password_verify($password, $db_user_password)) {
             $_SESSION['username'] = $db_username;
             $_SESSION['user_id'] = $db_user_id;
             $_SESSION['firstname'] = $db_user_firstname;
             $_SESSION['lastname'] = $db_user_lastname;
             $_SESSION['user_role'] = $db_user_role;

             redirect("./admin");


         } else {
             echo "<script>alert('Login again!');</script>";
             return false;
         }

     }
     return true;
 }

 function fetch_records($mysqli_result){
    return mysqli_fetch_array($mysqli_result);
 }


function recordCount($table){
    global $connection;
    
    if($table == 'Posts'){
        $query = "SELECT * FROM ".$table;
        $select_all = mysqli_query($connection, $query);
        confirm($select_all);
        return mysqli_num_rows($select_all);
    
    } else if($table=='Comments'){ //Comments
        $query = "SELECT * FROM $table INNER JOIN Posts ON comment_post_id = Posts.post_id";
        $select_all = mysqli_query($connection, $query);
        confirm($select_all);
        return mysqli_num_rows($select_all);
        
    } else{ //Category
        $query = "SELECT * FROM $table";
        $select_all = mysqli_query($connection, $query);
        confirm($select_all);
        return mysqli_num_rows($select_all);
    }
    
    
    
}


function confirm($result){
    global $connection;
    if(!$result){
        die("<br>"."Query failed! ".mysqli_error($connection));
    } else{
        //echo "Success!";
    }
}

function users_online(){
    if(isset($_GET['online_users'])){ //used in scripts.js, request sent via GET/POST mainly
        global $connection;

        if(!$connection){
            session_start(); //Initialize session data / resume existing session
            include("../includes/db.php");
        
            $session = session_id(); //unique for each browser app
            $time = time(); //current Unix timestamp
            $time_out_in_seconds = 05;
            $time_out = $time - $time_out_in_seconds;
        
            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $users_online = mysqli_query($connection, $query);
            $count = mysqli_num_rows($users_online);
        
            if($count==NULL){ //new user
                mysqli_query($connection,"INSERT INTO users_online(session,time) VALUES('$session','$time_out')");
            } else{
                mysqli_query($connection,"UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }
            //active
            $active_users = mysqli_query($connection,"SELECT * FROM users_online WHERE time > '$time_out'");
            $count_active_users = mysqli_num_rows($active_users);
            
            echo $count_active_users;
        } else{
            //echo "-1";
        }
        
    } else{
        //echo "-1";
    } 
    
}

users_online();

function is_admin($user_name){
    //Session variables are available throughout the whole application
    //We have to prevent duplicated usernames 

    global $connection;
    $query = "SELECT role FROM Users WHERE username = '$user_name'";
    $result = mysqli_query($connection, $query);
    confirm($result);

    $row = mysqli_fetch_array($result);
    if($row['role'] == 'admin'){
        return True;
    } else{
        return False;
    }
}

function username_exists($user_name){
    //prevent duplicates
    
    global $connection;
    $query = "SELECT username FROM Users WHERE username = '$user_name'";
    $result = mysqli_query($connection, $query);
    confirm($result);

    if(mysqli_num_rows($result) >= 1){
        return True;
    } else{
        return False;
    }

}

function email_exists($email){
    //prevent duplicates
    
    global $connection;
    $query = "SELECT user_email FROM Users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirm($result);

    if(mysqli_num_rows($result) >= 1){
        return True;
    } else{
        return False;
    }

}

function isLoggedIn(){
    if(isset($_SESSION['user_role']) || isset($_SESSION['username'])){
        return true;
    } else{
        return false;
    }
}

function redirect($location){
    header("Location: ".$location);
    exit; //terminate current script
}

function ifLoggedInWithRedirect($redirect_location=null){
    if(isLoggedIn()){
        redirect($redirect_location);
    }
}

function register_user($first_name, $last_name,$username, $email, $password){
    global $connection;

    //Make sure the fields aren't empty
    if(!empty($username) && !empty($password) && !empty($email)){
        //global $connection;
        
        //Consideration: SQL injection and encryption of passwords using crypt()
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);
        $email = mysqli_real_escape_string($connection, $email);
        $first_name = mysqli_real_escape_string($connection, $first_name);
        $last_name = mysqli_real_escape_string($connection, $last_name);

        if(username_exists($username) || email_exists($email)){

            $message = "<h5 class='text-danger text-center'>Username or email are already taken! Please try other patterns.</h5><br>";
            return $message;

        } else { //!username_exists && !email_exists
            $password = password_hash($password,PASSWORD_BCRYPT,array('cost'=>12));

            $add_user = "INSERT INTO Users(user_first_name, user_last_name,username,user_email,password,role) ";
            $add_user.="VALUES ('{$first_name}', '{$last_name}','{$username}','{$email}','{$password}','subscriber')";

            $register_user = mysqli_query($connection, $add_user);
            confirm($register_user);
            
            $message = "<h5 class='text-success text-center'>Your Registration has been submitted, congratulations!</h5><br>";
            return $message;
        }

    } else{
        $message =  "<h5 class='text-warning text-center'>One of your fields is empty.</h5><br>";
        return $message;
    }
    
    
    // if (CRYPT_BLOWFISH == 1) {
    //     echo 'Blowfish:     ' . crypt('{$password}', '$2a$07$usesomesillystringforsalt$') . "\n";
    // }

    // $query = "SELECT rand_salt FROM Users";
        // $get_rand_salt = mysqli_query($connection, $query);

        // if(!$get_rand_salt){
        //     die('Query failed '.mysqli_error($connection));
        // }

        // $row = mysqli_fetch_assoc($get_rand_salt);
        // $rand_salt = $row['rand_salt'];
        
        /*
            you should be aware that the salt is generated once. 
            If you are calling this function repeatedly, 
            this may impact both appearance and security
        
        $password = crypt($password, $rand_salt); */
        //----------------------------------------------------//

        /*compatible with crypt()
            Second argument is the supported algorithm (PASSWORD_DEFAULT and PASSWORD_BCRYPT)
            Third is the $option 
        */

        return "";
}

function insert_category(){
    global $connection;

    if(isset($_POST['submit'])){
        $cat_title = escape($_POST['cat_title']);
        if($cat_title=="" || empty($cat_title)){
            echo "<h5>Category input cannot be empty!</h5>\n";
        }

        else{
            $query="INSERT INTO Category(cat_title) ";
            $query.="VALUE('{$cat_title}')";
            $create_category = mysqli_query($connection, $query);
            
            if(!$create_category){
                die('Query failed! '.mysqli_error($connection));
            }
            echo "<h5>Category just added</h5>\n";
        }
    }
}

function find_all_categories(){
    global $connection;
    $query = "SELECT * FROM Category";
    $select_all_categories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_all_categories)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Update</a></td>";
        echo "</tr>";
    }
}

function delete_category(){
    global $connection;
    
    if(isset($_GET['delete'])){
        $cat_id_deleted = escape($_GET['delete']);
        $delete_query = "DELETE FROM Category WHERE cat_id = {$cat_id_deleted}";
        $result = mysqli_query($connection, $delete_query);
        header("Location: categories.php");
    } 
}

?>