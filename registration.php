<?php  include "includes/header.php"; ?>


    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>

   <div class="container">
    <form method="GET" class="navbar-form navbar-right" action="" id="lang_form">
        <div class="form-group">
            <select name="lang" class="form-control" onchange="changeLanguage()">
                
                <option value="en" <?php if(isset($_SESSION['lang']) && $_GET['lang']=='en'){echo "selected"; } ?>>English</option>
                <option value="esp" <?php if(isset($_SESSION['lang']) && $_GET['lang']=='esp'){echo "selected"; } ?>>Español</option>
                <option value="fren" <?php if(isset($_SESSION['lang']) && $_GET['lang']=='fren'){echo "selected"; } ?>>Français</option>
            </select>
        </div>
    </form>
   </div>
    
    <?php

        require 'vendor/autoload.php'; //notifications get pushed upon user registration - dashboard.pusher.com
        //$dotenv = Dotenv\Dotenv::create(__DIR__);
        $dot_env = \Dotenv\Dotenv::create(__DIR__);
        $dot_env->load();

        if(isset($_GET['lang']) && !empty($_GET['lang'])){
            //choosing a language

            $_SESSION['lang'] = $_GET['lang'];

            //reload the page
            if($_SESSION['lang']!=$_GET['lang']){
                echo "<script type='text/javascript'>location.reload();</script>";
            }
            if(isset($_SESSION['lang'])){
                include "./includes/lang/".$_SESSION['lang'].".php";
            }
        } else{
            $_GET['lang'] = 'en';
            $_SESSION['lang'] = $_GET['lang'];
            include "./includes/lang/en.php";
        }
        

        $options = array(
            'cluster' => 'ap3',
            'encrypted' => true
          );

        /*
        All of the defined variables are now accessible with the getenv method, 
        and are available in the $_ENV and $_SERVER super-globals.
        */
        $pusher = new \Pusher\Pusher(getenv('APP_KEY'), getenv('APP_SECRET'), getenv('APP_ID'), $options);

        $message_displayed = '';

        if(isset($_POST['submit'])){ //$_SERVER['REQUEST_METHOD] == 'POST'
            //trim " \t\n\r\0\x0B"
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
                    

            $error = [
                'username' => '',
                'email' => '',
                'password' => ''
            ];

            if(strlen($username) <3){
                $error['username'] = "Username too short! Try generating another username";
            }

            if(username_exists($username)){
                $error['username'] = "<h5 class='text-danger text-center'>The username already exists. Try other patterns.</h5><br>";
            }

            if(empty($username)){
                $error['username'] = "<h5 class='text-warning text-center'>The username field entry cannot be empty.</h5><br>";
            }

            //--email
            if(strpos($email, "@") == -1){
                $error['email'] = "<h5 class='text-warning text-center'>Email expression isn't correct!</h5>";
            }

            if(email_exists($email)){
                $error['email'] = "<h5 class='text-danger text-center'>The email already exists. Try other patterns.</h5><br>";
            }

            if(empty($email)){
                $error['email'] = "<h5 class='text-warning text-center'>The email address field entry cannot be empty.</h5><br>";
            }

            //--password validation
            if(strlen($password) < 8){
                $error['password'] = "<h5 class='text-warning text-center'>Password too short! Try generating a longer password</h5><br>";
            }

            if(empty($password)){
                $error['password'] = "<h5 class='text-warning text-center'>The password field entry cannot be empty.</h5><br>";
            }


            foreach($error as $key => $value){
                if(empty($value)){
                    //no errors, register the user
                    //$message_displayed = register_user($first_name, $last_name, $username, $email, $password);
                    unset($error[$key]);
                }
            }

            if(empty($error)){
                $message_displayed = register_user($first_name, $last_name, $username, $email, $password);
                //echo $message_displayed;

                $pusher->trigger('notification', 'new_user', $username); //Trigger an event by providing event name and payload.
                //Pusher Javsacript library code needed - check below

                login_user($username, $password);
            }
        }
    ?>   
 
<!-- Page Content -->
<div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1 class='text-center text-info'><?php echo _REGISTER; ?></h1><br>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        <?php 
                        // if($message == "Your Registration has been submitted"){
                        //     echo "<h5 class='text-success text-center'>".$message."</h5>";
                        // } else{
                        //     echo "<h5 class='text-danger text-center'>".$message."</h5>";
                        // }
                        ?>

                        <div class="form-group">
                            <label for="first_name" class="sr-only">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="<?php echo _FIRST_NAME?>" autocomplete="on">

                        </div>
                        <div class="form-group">
                            <label for="last_name" class="sr-only">First Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="<?php echo _LAST_NAME?>" autocomplete="on">
                        </div>
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo _USERNAME?>" autocomplete="on"
                            value = "<?php echo isset($username) ? $username: ''?>">
                            <p><?php echo isset($error['username']) ? $error['username']: '' ?></p>

                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo _EMAIL?>" autocomplete="on"
                            value = "<?php echo isset($email) ? $email: ''?>">
                            <p><?php echo isset($error['email']) ? $error['email']: '' ?></p>
                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="<?php echo _PASSWORD?>">
                            <p><?php echo isset($error['password']) ? $error['password']: '' ?></p>
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="<?php echo _REGISTER?>" onclick='transform_data();'>
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


        <hr>

        <script>
            //onchange is an event handler
            function changeLanguage(){
                document.getElementById('lang_form').submit();
            }
        </script>

<?php include "includes/footer.php";?>

<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
<script>
    //This page includes the JavaScript below. Connection state: Connected
    
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('c905f034f3bb3aea9a15', {
      cluster: 'ap3',
      forceTLS: true
    });

    var channel = pusher.subscribe('notification');
    channel.bind('new_user', function(data) {
      alert(JSON.stringify(data));
      console.log(data.message);
    });

</script>