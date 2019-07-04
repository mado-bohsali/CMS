<?php

if(isset($_POST['create_post'])){
    //echo $_POST['create_post'];
    $post_title = escape($_POST['title']);
    $post_category_id = escape($_POST['post_category']);
    $post_author = escape($_POST['author']);
    $post_status = escape($_POST['post_status']);
    $post_image = $_FILES['post_image']['name'];
    $post_image_temp = $_FILES['post_image']['tmp_name'];
    $post_tags = escape($_POST['post_tags']);
    $post_date = date('d-m-y');
    $post_content = escape($_POST['post_content']);
    move_uploaded_file($post_image, "../images/$post_image");

    $query = "INSERT INTO Posts(post_title,post_category_id,post_author,post_status,post_image,post_tags,post_date,post_content) ";
    $query.="VALUES ('{$post_title}',{$post_category_id},'{$post_author}','{$post_status}','{$post_image}','{$post_tags}',now(),'{$post_content}')";

    $create_post = mysqli_query($connection, $query);

    if(!$create_post){
        confirm($create_post);
        $post_id = mysqli_insert_id($connection); //Returns the auto generated id used in the last quer
        echo "<p class='bg-success'>Post created!<a href='../post.php?p_id={$post_id}'> View post</a></p>";

    } else{

    }
}
?>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="title">
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
        <input type="text" class="form-control" name="author">
    </div>

    <div class="form-group">
        <label for="post_status">Post Status</label>
        
        <select name="post_status" id="">
            <option value="draft">Post status</option>
            <option value="publish">Publish</option>
            <option value="draft">Draft</option>
        </select>
        
    </div>

    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" class="form-control" name="post_image"> <!--saves in a temporary location -->
    </div>

    <div class="form-group">
        <label for="post_date">Post date</label>
        <input type="date" class="form-control" name="date">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"></textarea>
    </div>

    <!-- <script>
        ClassicEditor
            .create( document.querySelector( '#body' ) )
            .catch( error => {
                console.error( error );
            } );
    </script> -->

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_post" value="Publish post">
    </div>

</form>
