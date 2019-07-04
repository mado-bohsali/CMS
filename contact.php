 <?php  include "includes/header.php"; ?>

    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>
    
    <?php
        if(isset($_POST['submit'])){
            $to = "moebee95@yahoo.com";
            $sender_email = escape($_POST['email']);
            $subject = escape($_POST['subject']);
            $message = escape($_POST['body']);
            $header = "From: ".$sender_email;

            /*
            (Windows only) When PHP is talking to a SMTP server directly, if a full stop is found on the start of a line, it is removed. 
            To counter-act this, replace these occurrences with a double dot.
            */

            if($sender_email!=NULL && $subject!=NULL & $message!=NULL){
                // use wordwrap() if lines are longer than 70 characters
                $message = wordwrap($message,70);

                // send email
                mail($to,$subject,$message,$header);
                echo "<h4 style=\"text-align:center;\" class='text-success'>Message sent! Shortly, we'll get back to you.</h4>";
            
            } else{
                echo "<script>alert('One or more fields are missing');</script>";
                
            }
            
        }


    ?>   
 
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-4 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Contact us anytime!</h1>
                    <form role="form" action="contact.php" method="post" id="login-form" autocomplete="off">
                        
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                        </div>
                        <div class="form-group">
                            <label for="subject" class="sr-only">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject title">
                        </div>

                        <div class="form-group">
                            
                            <textarea name="body" id="body" cols="55" rows="10" style="margin: 0px; width: 360px; height: 207px;"></textarea>
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Send">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


        <hr>



<?php include "includes/footer.php";?>
