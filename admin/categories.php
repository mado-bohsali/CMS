<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>
        
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to admin! <?php echo $_SESSION['username']; ?>
                            
                        </h1>

                        <div clas="col-xs-6">
                            <?php insert_category(); //Adding new category script ?>                     
                            
                            <form action="" method="POST"> 
                                <div class="form-group">
                                    <label for="cat-title">Adding categories:</label>
                                    <input class="form-control" type="text" name="cat_title">
                                </div>

                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" name="submit" value="Add a category">
                                </div>
                            </form>

                            <form action="" method="POST"> 
                                <div class="form-group">
                                    <label for="cat-title">Editing categories:</label>
                                    <?php

                                    if(isset($_GET['edit'])){ //?edit=
                                        $cat_id_editted = escape($_GET['edit']);
                                        $query = "SELECT * FROM Category WHERE cat_id = {$cat_id_editted}";
                                        $select_a_categories = mysqli_query($connection, $query);

                                        while($row = mysqli_fetch_assoc($select_a_categories)){
                                            $cat_id = $row['cat_id'];
                                            $cat_title = $row['cat_title'];
                                    ?>

                                    <input value="<?php if(isset($cat_title)){ echo $cat_title;} ?>" class="form-control" type="text" name="cat_title">
                                    <?php }} ?>

                                    <?php //update a category
                                                                                 
                                        if(isset($_POST['update'])){
                                            $cat_title_updated = escape($_POST['cat_title']);
                                            $update_query = "UPDATE Category SET cat_title = '{$cat_title_updated}' WHERE cat_id = {$cat_id}";
                                            if(!$update_query){
                                                die("Editing failed!\n".mysqli_error($connection));
                                            }
                                            $result = mysqli_query($connection, $update_query);
                                            header("Location: categories.php");
                                        }
                                         
                                    ?>

                                    
                                </div>

                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" name="update" value="Update a category">
                                </div>
                            </form>
                        </div>

                        <!--Add a category form-->
                        <div clas="col-xs-6">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Category title</th>
                                        <th style="text-align:center" colspan="2">Action</th>

                                    </tr>
                                </thead>

                                <tbody>
                                <?php find_all_categories(); //Finding all categories ?>

                                <?php delete_category(); //delete ID
                                ?>
                                
                            </tbody>
                            </table>
                        </div>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> Blank Page
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
        <?php include "includes/admin_footer.php"; ?>