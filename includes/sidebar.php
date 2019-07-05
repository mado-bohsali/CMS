<?php ob_start(); //buffering the requests ?> 

<?php
if(isset($_SESSION['username'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    #login_form{
        display: none;
    }
</style>
</head>
<html>

<?php
} else{  }
?>

<!-- Blog Sidebar Widgets Column -->
<div class="col-md-4">                

<!--Login-->
<div class="well" id="login_form">
    <h4>Login to my blog</h4>
    <form action="login.php" method="POST">
        
        <div class="form-group">
            <input name="username" type="text" class="form-control" placeholder="username">
        </div>

        <div class="input-group">
            <input name="password" type="password" class="form-control" placeholder="password">
            <span class="input-group-btn">
                <button name="login" class="btn btn-primary" type="submit">Sign in</button>
            </span>
            
        </div>

        <br/>
            <a class='stretched-link' href='forgot.php'><b>I forgot my password!</b></a>
            
    </form> <!--search form-->
    <!-- /.input-group -->
</div>

<!-- Blog Search Well -->
<div class="well">
    <h4>Blog Search</h4>
    <form action="search.php" method="POST">
        <div class="input-group">
            <input name="search" type="text" class="form-control">
            <span class="input-group-btn">
                <button name="submit" class="btn btn-default" type="submit">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
    </form> <!--search form-->
    <!-- /.input-group -->
</div>


<!-- Blog Categories Well -->
<div class="well">
    <?php
    $query = "SELECT * FROM Category LIMIT 3";
    $select_categories_sidebar = mysqli_query($connection, $query);

    ?>

    <h4>Blog Categories</h4>
    <div class="row">
        <div class="col-lg-12">
            <ul class="list-unstyled">
                <?php

                while($row = mysqli_fetch_assoc($select_categories_sidebar)){
                    $cat_title = $row['cat_title'];
                    $cat_id = $row['cat_id'];
                    echo "<li><a href='category.php?category_id=$cat_id'>{$cat_title}</li>";
                }
                ?>
            </ul>
        </div>   
    </div>
    <!-- /.row -->
</div>

<!-- Side Widget Well -->
<?php include "widget.php"; ?>

</div>
