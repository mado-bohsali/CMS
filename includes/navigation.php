<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./">CMS Home</a> <!--cms/-->
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php 
                    
                    include "includes/db.php";
                    include_once "admin/functions.php";

                    $query = "SELECT * FROM Category";
                    $select_all_categories = mysqli_query($connection, $query);

                    while($row = mysqli_fetch_assoc($select_all_categories)){
                        $cat_title = $row['cat_title'];
                        $cat_id = $row['cat_id'];
                        
                        $class = '';
                        //$registration_class = '';

                        $registration = 'registration.php';
                        $page_name = basename($_SERVER['PHP_SELF']); //Returns filename component of path

                        if(isset($_GET['category']) && $_GET['category']==$cat_title){
                            $class='active';
                        } else if($page_name == $registration){
                            $class = 'active';
                        }

                        echo "<li class='{$class}'><a href='category.php?category_id={$cat_id}'>{$cat_title}</li>";

                        
                    }

                    if(isset($_SESSION['username'])){
                        if(is_admin($_SESSION['username'])){
                            echo "<li><a href='./admin/index.php'>Admin</a></li>";
                            echo "<li><a href='./contact'>Contact us</a></li>";
                        }
                        
                    } else{
                        echo "<li class='$class'><a href='./registration'>Registration</a></li>";
                        echo "<li><a href='./contact'>Contact us</a></li>";
                        echo "<li><a href='./login'>Login</a></li>";
                    }

                    
                    
                    if(isset($_SESSION['username'])){
                        
                    ?>
                    <div class="container">
                        <ul class="nav navbar-right top-nav">
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> 
                                    <?php 
                                    if(isset($_SESSION['username'])){
                                        echo $_SESSION['username'];  
                                    }
                                    ?>
                                    <b class="caret"></b>
                                </a>
                                
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                        <?php
                        if(isset($_GET['p_id'])){
                            $post_id = escape($_GET['p_id']);
                            echo "<li><a href='./admin/posts.php?source=edit_post&p_id={$post_id}'>Edit Post</a></li>";
                        }
                    }
                    ?>
                   
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        
        <!-- /.container -->
    </nav>