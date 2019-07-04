<?php include "includes/header.php";?>
<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <?php //include "includes/db.php"; REPEATED inclusion
                
                if(isset($_GET['category_id'])){
                    $post_category_id = escape($_GET['category_id']);
                     
                    $query = "SELECT * FROM Posts WHERE post_status = 'Published' AND post_category_id = {$post_category_id}";
                    $select_all_posts = mysqli_query($connection, $query);
    
                    if(mysqli_num_rows($select_all_posts) < 1){
                        echo "<h1 class='text-center'>No posts available in categories</h1>";
                    } else{
                    while($row = mysqli_fetch_assoc($select_all_posts)){
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

                    ?>

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
                        <p><?php echo $post_content; ?></p>
                        <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                        <hr>

                <?php } }
                
                } else{
                    header("Location: index.php");
                }

                ?>

                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>
<?php include "includes/footer.php"; ?>
