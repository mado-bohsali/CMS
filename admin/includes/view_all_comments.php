<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>
            <th>In response to</th>
            <th>Date</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>


    <tbody>
        <?php
        global $connection;
        $query = "SELECT * FROM Comments";
        $select_all_comments = mysqli_query($connection, $query);

        if(!$select_all_comments){
            die('Query Failed '.mysqli_error($connection));
        }
    
        while($row = mysqli_fetch_assoc($select_all_comments)){
            $comment_id = $row['comment_id'];
            $comment_post_id = $row['comment_post_id'];
            $comment_author = $row['comment_author'];
            $comment_content = $row['comment_content'];
            $comment_status = $row['comment_status'];
            $comment_email = $row['comment_email'];
            $comment_date = $row['comment_date'];

            echo "<tr>";
            echo "<td>$comment_id</td>";
            echo "<td>$comment_author</td>";
            echo "<td>$comment_content</td>";
            echo "<td>$comment_email</td>";
            echo "<td>$comment_status</td>";

            $query = "SELECT * FROM Posts WHERE post_id = $comment_post_id";
            $selected_post_id = mysqli_query($connection,$query);

            while($row = mysqli_fetch_assoc($selected_post_id)){
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];
                echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
            }

            echo "<td>$comment_date</td>";
            echo "<td><a href='./comments.php?approve={$comment_id}'>Approve</a></td>";
            echo "<td><a href='./comments.php?unapprove={$comment_id}'>Disapprove</a></td>";
            echo "<td><a href='./comments.php?delete={$comment_id}'>Delete</a></td>";
            echo "</tr>";
        }
       
        ?>
    </tbody>
</table> 

<?php

if(isset($_GET['delete'])){
    //delete comment
    $delete_comment_id = escape($_GET['delete']);
    $query = "DELETE FROM Comments WHERE comment_id = {$delete_comment_id}";
    $result = mysqli_query($connection, $query);
    header("Location: comments.php");
}

if(isset($_GET['unapprove'])){
    $unapproved_comment_id = escape($_GET['unapprove']);
    $query = "UPDATE Comments SET comment_status = 'Unapproved!' WHERE comment_id = $unapproved_comment_id";
    $result = mysqli_query($connection, $query);
    header("Location: comments.php");
}

if(isset($_GET['approve'])){
    $approved_comment_id = escape($_GET['approve']);
    $query = "UPDATE Comments SET comment_status = 'Approved!' WHERE comment_id = $approved_comment_id";
    $result = mysqli_query($connection, $query);
    header("Location: comments.php");
}

?>