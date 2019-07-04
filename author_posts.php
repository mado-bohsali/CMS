<?php include "includes/header.php";?>
<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-8">


                <?php //include "includes/db.php"; REPEATED inclusion
                if(isset($_GET['p_id'])){
                    $selected_post_id = escape($_GET['p_id']);
                    $selected_post_author = escape($_GET['author']);
                }

            $query = "SELECT * FROM Posts WHERE post_author = '{$selected_post_author}'";
                    $select_author_posts = mysqli_query($connection, $query);
                    
                    while($row = mysqli_fetch_assoc($select_author_posts)){
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];


                ?>
                    <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                    </h1>

                    <!-- First Blog Post -->
                    <h2>
                        <a href="post.php?p_id=<?php echo $selected_post_id; ?>"><?php echo $post_title; ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts.php?author=<?php echo $selected_post_author; ?>&p_id=<?php echo $selected_post_id; ?>"><?php echo $selected_post_author; ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date; ?></p>
                    <hr>
                    <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                    <hr>

                <?php }

                ?>
            <!-- Blog Comments -->
                
                <?php
                if(isset($_POST['create_comment'])){
                    $selected_id = escape($_GET['p_id']);
                    $comment_author = escape($_POST['comment_author']);
                    $comment_email = escape($_POST['comment_email']);
                    $comment_content = escape($_POST['comment_content']);

                    if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)){
                        $query = "INSERT INTO Comments (comment_post_id,comment_date,comment_author,comment_content,comment_status,comment_email) ";
                        $query.= "VALUES ('{$selected_id}',now(), '{$comment_author}', '{$comment_content}', 'unapproved', '{$comment_email}')";
                    
                        $create_comment = mysqli_query($connection,$query);
    
                        if(!$create_comment){
                            die("<br>".'Query Failed. '.mysqli_error($connection));
                        }
    
                        $query = "UPDATE Posts SET post_comment_count = post_comment_count+1 ";
                        $query.= "WHERE post_id = $selected_id";
    
                        $update_comment_count = mysqli_query($connection,$query);
                    } else{
                        echo "<script> alert('Fields cannot be empty!'); </script>";
                    }
                }

                ?>

                Comments Form
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form action="" role="form" method="POST">
                        <label for="Author">Author</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="comment_author">
                        </div>

                        <label for="Email">Email</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="comment_email">
                        </div>

                        <label for="Comment">Comment</label>
                        <div class="form-group">
                            <textarea name="comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
                <?php
                //$selected_id = $_GET['p_id'];
                $query = "SELECT * FROM Comments WHERE comment_post_id = {$_GET['p_id']} ";
                $query.= "AND comment_status = 'Approved!' ";
                $query.= "ORDER BY comment_id DESC ";
                $select_comments_by_id = mysqli_query($connection, $query);

                if(!$select_comments_by_id){
                    die('Query failed '.mysqli_error($connection));
                }

                while($row = mysqli_fetch_assoc($select_comments_by_id)){
                    $comment_date = $row['comment_date'];
                    $comment_content = $row['comment_content'];
                    $comment_author = $row['comment_author'];
                ?>
                
                 <!-- Comment -->
                 <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author; ?>
                            <small><?php echo $comment_date; ?></small>
                        </h4>

                        <?php echo $comment_content; ?>
                    </div>   
                </div>

                <?php }
                ?>
        
            </div>
            
            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->
    </div>
        <hr>
<?php include "includes/footer.php"; ?>
