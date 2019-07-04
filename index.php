<?php include "includes/header.php";?>
<!-- Navigation -->
<?php include "includes/navigation.php";
      include_once "admin/functions.php";
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <?php //include "includes/db.php"; REPEATED inclusion
                
                if(isset($_GET['page'])){
                    $page = escape($_GET['page']);

                } else{
                    $page = "";
                }

                if($page=="" || $page==1){
                    $page_1 = 0;
                } else{
                    $page_1 = ($page*2)-2; //how many posts per page
                }
                
                $posts_count = "SELECT * FROM Posts WHERE post_status='published'";
                $result = mysqli_query($connection, $posts_count);
                $count = mysqli_num_rows($result);
                

                if($count < 1){
                    echo "<h1 class='text-center'>No posts published yet!</h1>";
                } else{
                
                $count = ceil($count/2);
                $query = "SELECT * FROM Posts WHERE post_status = 'published' LIMIT $page_1, 2";
                $select_all_posts = mysqli_query($connection, $query);

                while($row = mysqli_fetch_assoc($select_all_posts)){
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = substr($row['post_content'],0,50); //truncating
                    $post_status = $row['post_status'];

                ?>
                    <h1 class="page-header">
                        All blogs compiled!
                        <small>skim quickly</small>
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
                    
                    
                    <a href="post.php?p_id=<?php echo $post_id; ?>">
                        <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                    </a>
                    <hr>
                    <p><?php echo $post_content; ?></p>
                    <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read more
                        <span class="glyphicon glyphicon-time"></span></a>    
                    <hr>
                    

                <?php }
                }

                ?>

                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        <ul class="pager">
            <?php
            for($i=1; $i<=$count; $i++){
                if($i==$page){
                    echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
                } else{
                    echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
                }
                
            }
            ?>
        </ul>
<?php include "includes/footer.php"; ?>
