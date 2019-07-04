<?php include "includes/header.php";?>

<?php include "./admin/functions.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

    <?php
        if(isset($_POST['liked'])){
            $search_post = "SELECT * FROM Posts WHERE post_id = {$_POST['post_id']}"; //within <script> tag
            $result = mysqli_query($connection, $search_post);
            
            $post_result_array = mysqli_fetch_array($result); //assoc array returned

            $likes = $post_result_array['likes'];
            

            //update likes field for that post
            mysqli_query($connection, "UPDATE Posts SET likes = {$likes}+1 WHERE post_id = {$GET['p_id']}");

            ?>
            <script>
                var likes = <?php echo $likes; ?>;

                console.log('likes for post: '+likes);
            </script>
        <?php 
        
        } else if(isset($_POST['unliked'])){
            $query = "SELECT * FROM Posts WHERE post_id = {$_POST['post_id']}";
            $result = mysqli_query($connection, $query);

            $post_result_array = mysqli_fetch_array($result);
            $likes = $post_result_array['likes'];

            mysqli_query($connection, "DELETE FROM Likes WHERE post_id = {$_POST['post_id']} AND user_id = {$_POST['user_id']}");
            mysqli_query($connection, "UPDATE Posts SET likes=$likes-1 WHERE WHERE post_id = {$_POST['post_id']}");

            exit();

        } else {
            echo "<h2 class='text-center'>Please click like!</h2>";
        }

    ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-8">


                <?php //include "includes/db.php"; REPEATED inclusion
                if(isset($_GET['p_id'])){
                    $selected_post_id = escape($_GET['p_id']);

                    $views_query = "UPDATE Posts SET post_views_count = post_views_count+1 WHERE post_id = {$selected_post_id}";
                    $views_result = mysqli_query($connection, $views_query);

                    if(!$views_result){
                        die("Query failed");
                    }

                    if(isset($_SESSION['user_role']) && $_SESSION['user_role']=='admin'){
                        $query = "SELECT * FROM Posts WHERE post_id = $selected_post_id";
                    }

                    $query = "SELECT * FROM Posts WHERE post_id = $selected_post_id AND post_status='Published'";
                    $select_all_posts = mysqli_query($connection, $query);
                    
                    while($row = mysqli_fetch_assoc($select_all_posts)){
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
                    }

                    $published_posts = mysqli_num_rows($select_all_posts);

                    if($published_posts < 1){
                       echo "<h1 class='text-center'>No published posts yet!</h1>"; 
                    }

                    

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
                        by <a href="author_posts.php?author=<?php echo $post_author; ?>&p_id=<?php echo $selected_post_id; ?>"><?php echo $post_author; ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date; ?></p>
                    <hr>
                    <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                    <hr>

                    <div class="row">
                        <p class="pull-right"><a class="like" href=''><span class="glyphicon glyphicon-thumbs-up"></span> Like</a></p>
                    </div>

                    <div class="row">
                        <p class="pull-right"><a class="unlike" href=''><span class="glyphicon glyphicon-thumbs-down"></span> Unlike</a></p>
                    </div>

                <?php }

                else{
                    header("Location: index.php");
                }

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
                        $query.= "VALUES ('{$selected_id}',now(), '{$comment_author}', '{$comment_content}', 'Approved!', '{$comment_email}')";
                    
                        $create_comment = mysqli_query($connection,$query);
    
                        if(!$create_comment){
                            die("<br>".'Query Failed. '.mysqli_error($connection));
                        }
    
                        // $query = "UPDATE Posts SET post_comment_count = post_comment_count+1 ";
                        // $query.= "WHERE post_id = $selected_id";
    
                        $update_comment_count = mysqli_query($connection,$query);
                    }
                }

                ?>

                <!-- Comments Form -->
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
                $selected_p_id = escape($_GET['p_id']);
                $query = "SELECT * FROM Comments WHERE comment_post_id = {$selected_p_id} ";
                $query.= "AND comment_status = 'Approved!' ";
                $query.= "ORDER BY comment_id DESC ";
                $select_comments_by_id = mysqli_query($connection, $query);

                if(!$select_comments_by_id){
                    die('Query failed '.mysqli_error($connection));
                }

                $comment_date = '';
                $comment_content = '';
                $comment_author = '';

                while($row = mysqli_fetch_assoc($select_comments_by_id)){
                    $comment_date = $row['comment_date'];
                    $comment_content = $row['comment_content'];
                    $comment_author = $row['comment_author'];
                ?>
                
                 <!-- Comment -->

                
                <?php
                $accepted_comments = mysqli_num_rows($select_comments_by_id);

                if($accepted_comments < 1){
                    echo "<h1 class='text-center'>No comments for this post yet!</h1>"; 
                } else{

                ?>
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

            <?php } } ?>
        
            </div>
            
            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->
    </div>
        <hr>
<?php include "includes/footer.php"; ?>

<script>
    $(document).ready(function(){
        var post_id = <?php echo $_GET['p_id']; ?>;
        var user_id = 
        $('.like').click(function(){
            //use AJAX to increase number of likes
            //A set of key/value pairs that configure the Ajax request.

            $.ajax({
                url: "./post.php?p_id="+post_id,
                type: 'post',
                data: { //indeces of POST associative array, form data in headers
                    'liked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                }
            });

            console.log($.ajax(data));
        });

        //Unlike:
        
        $('.unlike').click(function(){
            //use AJAX to increase number of likes
            //A set of key/value pairs that configure the Ajax request.

            $.ajax({
                url: "./post.php?p_id="+post_id,
                type: 'post',
                data: { //indeces of POST associative array, form data in headers
                    'unliked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                }
            });

            console.log($.ajax(data));
        })
    }); 
</script>

