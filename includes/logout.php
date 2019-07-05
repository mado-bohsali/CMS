<?php ob_start(); ?>
<?php session_start();

//cancel the session - null assignment
$_SESSION['username'] = null;
$_SESSION['first_name'] = null;
$_SESSION['last_name'] = null;
$_SESSION['user_role'] = null;

header("Location: ../index.php");

?>