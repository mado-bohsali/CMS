<?php

if(isset($_GET['p_id'])){
    $selected_post_id = $_GET['p_id'];
}
global $connection;
$query = "SELECT * FROM Posts WHERE post_id = {$selected_post_id}";
$select_post_by_id = mysqli_query($connection, $query);

while($row = mysqli_fetch_assoc($select_post_by_id)){
    $post_id = $row['post_id'];
    $post_title = $row['post_title'];
    $post_category_id = $row['post_category_id'];
    $post_author = $row['post_author'];
    $post_status = $row['post_status'];
    $post_image = $row['post_image'];
    $post_tags = $row['post_tags'];
    $post_comment_count = $row['post_comment_count'];
    $post_content = $row['post_content'];
    $post_date = $row['post_date'];
}

if(isset($_POST['update_post'])){
    $post_title_updated = $_POST['title'];
    $cat_id_updated = $_POST['post_category'];
    $post_author_updated = $_POST['author'];
    $post_status_updated = $_POST['post_status'];

    $post_image = $_FILES['post_image']['name'];
    $post_image_temp = $_FILES['post_image']['tmp_name'];

    $post_tags_updated = $_POST['post_tags'];
    $post_content_updated = $_POST['post_content'];

    move_uploaded_file($post_image_temp, "../images/$post_image");
    
    if(empty($post_image)){
        $query = "SELECT * FROM Posts WHERE post_id = $selected_post_id";
        $select_image = mysqli_query($connection, $query);

        while($row = mysqli_fetch_array($select_image)){
            $post_image = $row['post_image'];
        }
    }

    $query = "UPDATE Posts SET ";
    $query.= "post_title = '{$post_title_updated}', ";
    $query.= "post_category_id = '{$cat_id_updated}', ";
    $query.= "post_author = '{$post_author_updated}', ";
    $query.= "post_date = now(), ";
    $query.= "post_status = '{$post_status_updated}', ";
    $query.= "post_image = '{$post_image}', ";
    $query.= "post_content = '{$post_content_updated}', ";
    $query.= "post_tags = '{$post_tags_updated}' ";
    $query.= "WHERE post_id = {$selected_post_id}";

    $update_post = mysqli_query($connection, $query);
    confirm($update_post);

    echo "<p class='bg-success'>Post updated!<a href='../post.php?p_id={$selected_post_id}'> View post</a></p>";
}


?>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title;?>" type="text" class="form-control" name="title">
    </div>

    <div class="form-group">
        <label for="post_category">Category name</label>
        <select style="padding-bottom:25px;" name="post_category" id="post_category">
            <?php
            $query = "SELECT * FROM Category";
            $select_a_category = mysqli_query($connection, $query);
            confirm($select_a_category);

            while($row = mysqli_fetch_assoc($select_a_category)){
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                echo "<option value='$cat_id'>$cat_title</option>"; //value and name hrefer to the same entity
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="post_author">Post Author</label>
        <input value="<?php echo $post_author;?>" type="text" class="form-control" name="author">
    </div>

    <div class="form-group">
        <select name="" id="">
            <option value='$post_status'><?php echo $post_status; ?></option>
            <?php
            if($post_status = 'Published'){
                echo "<option value='Draft'>Draft</option>";
            } else{
                echo "<option value='Published'>Published</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="post_status">Post Status</label>
        <input value="<?php echo $post_status;?>" type="text" class="form-control" name="post_status">
    </div>

    <div class="form-group">
        <img src="../images/<?php echo $post_image; ?>" alt="" width="150" height="100">
        <label for="post_image">Post Image</label>
        <input type="file" class="form-control" name="post_image"> <!--saves in a temporary location -->
    </div>

    <div class="form-group">
        <label for="post_date">Post date</label>
        <input value="<?php echo $post_date; ?>" type="date" class="form-control" name="date">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags;?>" type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"><?php echo str_replace('\r\n','</br>',$post_content); ?>
                
        </textarea>
    </div>

    <!-- <script>
        ClassicEditor
            .create( document.querySelector( '#body' ) )
            .catch( error => {
                console.error( error );
            } );
    </script> -->

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Publish post">
    </div>

</form>