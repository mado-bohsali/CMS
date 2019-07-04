<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"> Welcome to admin dashboard <small>dear <?php echo $_SESSION['first_name']; ?></small></h1>
                       <?php
                       

                       if(isset($_GET['source'])){
                            $source = escape($_GET['source']);
                            echo "<script>alert(".$source.");</script>";

                            if($source === 'add_post'){
                                echo "<script>alert('Adding a post...');</script>";
                                include "./includes/add_post.php";

                            } else if($source === 'edit_post'){
                                echo "<script>alert('Editing a post...');</script>";
                                include "./includes/edit_post.php";   
                            } else{
                                //nothing
                            }
                        } else{
                            include "./includes/view_all_posts.php";
                        }

                    //    switch($_GET['source']){
                    //     case 'add_post':
                            
                    //     break;

                    //     case 'edit_post':
                    //     break;
                        
                    //     default:
                    //     break;
                    //    }
                       
                       
                       ?>
                    </div>
                </div>
            </div>
    </div>
</div>
<?php include "includes/admin_footer.php"; ?>