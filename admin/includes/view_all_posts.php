<?php

//include("delete_modal.php");

if(isset($_POST['checkBoxArray'])){ //at least an ID is selected

    $selected_check_boxes = escape($_POST['checkBoxArray']);

    foreach($selected_check_boxes as $check_box_value){
        $bulk_options = escape($_POST['bulk_options']);

        switch($bulk_options){

            case 'Publish':
                $query = "UPDATE Posts SET post_status = 'Published' "; 
                $query.= "WHERE post_id = {$check_box_value}";
                $update_status = mysqli_query($connection, $query);
                confirm($update_status);
                break;

            case 'Delete':
                $query = "DELETE FROM Posts WHERE post_id = {$check_box_value}";
                $delete_row = mysqli_query($connection, $query);
                confirm($delete_row);
                break;

            case 'Draft':
                $query = "UPDATE Posts SET post_status = '{$bulk_options}' WHERE post_id = {$check_box_value}";
                $update_status = mysqli_query($connection, $query);
                confirm($update_status);
                break;

            case 'Clone':
                $query = "SELECT * FROM Posts WHERE post_id = '{$check_box_value}'";
                $clone_post = mysqli_query($connection, $query);
                confirm($clone_post);

                while($row = mysqli_fetch_assoc($clone_post)){
                    $clone_post_id = $row['post_id'];
                    $clone_post_title = $row['post_title'];
                    $clone_post_author  = $row['post_author'];
                    $clone_post_status = $row['post_status'];
                    $clone_post_tags = $row['post_tags'];
                    $clone_post_date = $row['post_date'];
                    $clone_post_image = $row['post_image'];
                    $clone_post_content = $row['post_content'];
                    $clone_post_category_id = $row['post_category_id'];
                }
                
                $query = "INSERT INTO Posts(post_title,post_category_id,post_author,post_status,post_image,post_tags,post_date,post_content) ";
                $query.="VALUES ('{$clone_post_title}',{$clone_post_category_id},'{$clone_post_author}','{$clone_post_status}','{$clone_post_image}','{$clone_post_tags}','{$clone_post_date}','{$clone_post_content}')";

                $clone_post_result = mysqli_query($connection, $query);
                confirm($clone_post_result);

                if(!$clone_post_result){
                    die("Cloning failed! ".mysqli_error($connection));
                }
                break;

            case 'default': //nothing
                
        }

    }
}

?>

<form action="" method='POST'>
<table class="table table-bordered table-hover">
    
    <div id="bulkOptionsContainer" class="col-xs-4">
        <select class="form-control" name="bulk_options" id="">
            <option value="">Select Options</option>
            <option value="Publish">Publish</option>
            <option value="Draft">Draft</option>
            <option value="Delete">Delete</option>
            <option value="Clone">Clone</option>

        </select>
    </div>

    <div class="col-xs-4">
        <input type="submit" name="submit" class="btn btn-success" value="Apply">
        <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
    </div>

    <thead>
        <tr>
            <th><input id="selectAllBoxes" type="checkbox"></th>
            <th>Category</th>
            <th>Id</th>
            <th>Title</th>
            <th>Author</th>
            <th>Status</th>
            <th>Image</th>
            <th>Tags</th>
            <th>Views count</th>
            <th>Comments</th>
            <th>Date</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>


    <tbody>
        <?php
        global $connection;
        //$query = "SELECT * FROM Posts ORDER BY post_id DESC";
        $query = "SELECT posts.post_id, posts.post_category_id,posts.post_author, posts.post_title, posts.post_status, ";
        $query.= "posts.post_image, posts.post_tags, posts.post_views_count, posts.post_comment_count, posts.post_date, categories.cat_title, categories.cat_id FROM ";
        $query.= "Posts AS posts LEFT JOIN Category AS categories ON posts.post_category_id = categories.cat_id ";
        $query.= "ORDER BY posts.post_id DESC";

        $select_all_posts = mysqli_query($connection, $query);
    
        while($row = mysqli_fetch_assoc($select_all_posts)){
            $post_id = $row['post_id'];
            $post_category_id = $row['post_category_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_status = $row['post_status'];
            $post_image = $row['post_image'];
            $post_tags = $row['post_tags'];
            $post_views = $row['post_views_count'];
            $post_comment_count = $row['post_comment_count'];
            $post_date = $row['post_date'];
            $category_title = $row['cat_title'];
            $category_id = $row['cat_id'];

            echo "<tr>";

            ?>

            <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id;?>'></td>
            <!--****** checkBoxArray[] has post_ids as elements-->

            <?php
            // $query = "SELECT * FROM Category WHERE cat_id = {$post_category_id}";
            // $select_category_by_id = mysqli_query($connection, $query);

            // while($row = mysqli_fetch_assoc($select_category_by_id)){
            //     $cat_id = $row['cat_id'];
            //     $cat_title = $row['cat_title'];
            //     echo "<td>$cat_title</td>";
            // }
            echo "<td>$category_title</td>";
            echo "<td>$post_id</td>";
            echo "<td>$post_title</td>";
            echo "<td>$post_author</td>";
            echo "<td>$post_status</td>";
            echo "<td><img width='100' src='../images/$post_image'></td>";
            echo "<td>$post_tags</td>";
            echo "<td><a href='./posts.php?reset={$post_views}'</a>$post_views</td>";
            
            //Check performance w/ the below query
            $query = "SELECT * FROM Comments WHERE comment_post_id = $post_id";
            $comments = mysqli_query($connection, $query);

            $row = mysqli_fetch_assoc($comments);
            $comment_id = $row['comment_id'];
            $comment_counts = mysqli_num_rows($comments);

            
            echo "<td><a href='comments.php?id=$post_id'>$comment_counts</a></td>";
            echo "<td>$post_date</td>";
            echo "<td><a href='../post.php?p_id={$post_id}'>View</a></td>";
            echo "<td><a href='./posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
            echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_link'>Delete</a></td>";
            //echo "<td><a onClick=\"javascript: return confirm('Are you sure?'); \" href='./posts.php?delete={$post_id}'>Delete</a></td>";
            echo "</tr>";
            
        }
        
        ?>
        
    </tbody>
</table> 
</form>
<?php

if(isset($_GET['delete'])){
    //delete query
    $delete_post_id = esxape($_GET['delete']);
    $query = "DELETE FROM Posts WHERE post_id = {$delete_post_id}";
    $result = mysqli_query($connection, $query);
    header("Location: posts.php");
}

if(isset($_GET['reset'])){
    $reset_views_query = "UPDATE Posts SET post_views_count = 0 WHERE post_id = ".escape($_GET['reset'])." ";
    $result = mysqli_query($connection, $reset_views_query);
    header("Location: posts.php");
}

?>

<!-- <script>
    $(document).ready(function(){
        $(".delete_link").on('click', function(){
        var id = $(this).attr("rel");
        var delete_url = "posts.php?delete="+ id +" ";
        $(".modal_delete_link").attr("href",delete_url);
        $("#myModal").modal('show');
        //alert(delete_url);
     });
    });
</script> -->