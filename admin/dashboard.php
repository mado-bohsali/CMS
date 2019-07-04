
<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">

        <?php
        //check the connection included in the header
        // if($connection){
        //     //echo "<h4 style=\"color:white\">Successful session!</h4>\n";
        // }

        ?>
        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="page-header">
                            Welcome to the admin page dear
                            <?php 
                                global $connection;
                                $username_session = $_SESSION['username'];
                                $query = "SELECT user_first_name, user_last_name,username, user_email, role FROM Users WHERE username = '{$username_session}'";

                                $session_by_username = mysqli_query($connection, $query);
                                confirm($session_by_username);

                                while($row = mysqli_fetch_assoc($session_by_username)){
                                    $_SESSION['first_name'] = $row['user_first_name'];
                                    $_SESSION['last_name'] = $row['user_last_name'];
                                    $_SESSION['user_role'] = $row['role'];
                                    $_SESSION['user_email'] = $row['user_email'];
                                }
                                echo $_SESSION['first_name']."!";
                            ?>
                        </h2>
                        
                        
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Blank Page
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                       
                <!-- Add Widgets -->
                
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-file-text fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php //How many posts
                                            $number_of_posts = recordCount("Posts"); 
                                            echo "<div class='huge'>". $number_of_posts. "</div>";
                                        ?>
                                        <div>Posts</div>
                                    </div>
                                </div>
                            </div>
                            <a href="./posts.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php //How many comments
                                            $number_of_comments = recordCount("Comments"); 
                                            echo "<div class='huge'>".$number_of_comments."</div>";
                                        ?>
                                    <div>Comments</div>
                                    </div>
                                </div>
                            </div>
                            <a href="comments.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php //How many comments
                                            $number_of_users = recordCount("Users");
                                            echo "<div class='huge'>".$number_of_users."</div>";
                                        ?>
                                        <div> Users</div>
                                    </div>
                                </div>
                            </div>
                            <a href="users.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-list fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <?php //How many comments 
                                            $number_of_categories = recordCount("Category");
                                            echo "<div class='huge'>".$number_of_categories."</div>";
                                        ?>
                                        
                                        <div>Categories</div>
                                    </div>
                                </div>
                            </div>
                            <a href="categories.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <?php

                $published_posts = checkStatusOrRole("Posts","post_status", "published");

                $draft_posts = checkStatusOrRole("Posts", "post_status", "draft");

                $unapproved_comments = checkStatusOrRole("Comments", "comment_status", "unapproved");

                $subscribers = checkStatusOrRole("Users", "role", "subscriber");



                ?>

                <div class="row">
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                            ['Data', 'Count'],
                                
                            <?php
                            //use two arrays
                            $elements = ['All posts','Active posts', 'Draft posts','Comments', 'Pending comments','Users','Subscribers','Categories'];
                            $values = [$number_of_posts, $published_posts, $draft_posts, $number_of_comments, $unapproved_comments, $number_of_users, $subscribers,$number_of_categories];

                            for($x=0; $x<7; $x++){
                                echo "[". "'{$elements[$x]}'" . "," .$values[$x] . "],";
                            }
                                echo "[". "'{$elements[7]}'" . "," .$values[7] . "]";
                            ?>
                            
                            ]);

                            var options = {
                            title: 'Data Distribution'
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                            chart.draw(data, options);
                        }
                    </script>
                    <div id="piechart" style="width: auto; height: 500px;"></div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
        
        <?php include "includes/admin_footer.php"; ?>
    
