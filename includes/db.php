<?php
ob_start();

//Connection creation: Database name = CMS
$db['db_host'] = 'localhost:XXXX';
$db['db_user'] = 'XXXX';
$db['db_password'] = 'XXXX';
$db['db_name'] = 'XXXX';

foreach($db as $key => $value){
    define(strtoupper($key), $value); //constant population and values
}
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if($connection){
    //echo "We are connected to CMS!\n";
} else{
    echo "<h2>Connection to CMS app failed!</h2>\n";
}
?>
