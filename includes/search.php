<?php include "includes/header.php";?>
<!-- Navigation -->
<?php include "includes/navigation.php";
      include "admin/functions.php";
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            <?php
                if(isset($_POST['submit'])){ //upon pressing search button
                    //echo $_POST['search'];
                    $search = escape($_POST['search']);
                    $query = "SELECT * FROM Posts WHERE post_tags LIKE '%$search%'"; //include wildcard before and after the input

                    $search_result = mysqli_query($connection, $query);
                    confirm($search_result);

                    $count_rows = mysqli_num_rows($search_result);

                    if($count_rows==0){ //no tag matches
                        echo "<h4>No results found</h4>";
                    } else{ //a match found in the pattern

                    while($row = mysqli_fetch_assoc(search_result)){
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = substr($row['post_content'],0,50); //truncating
                        $post_status = $row['post_status'];
                    ?>
                        <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                        </h1>

                        <!-- First Blog Post - display an individual post -->
                        <h2>
                            <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post_author; ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date; ?></p>
                        <hr>
                        <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore, veritatis, tempora, necessitatibus inventore nisi quam quia repellat ut tempore laborum possimus eum dicta id animi corrupti debitis ipsum officiis rerum.</p>
                        <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                        <hr>

                <?php }
                }
                ?>

                <?php
                }
                ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->
    </div>
        <hr>
<?php include "includes/footer.php"; ?>
