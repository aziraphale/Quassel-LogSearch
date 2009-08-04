<?php
// start silent session
@session_start();
// Begin da style
include_once('style/header.php');

if(isset($_SESSION['pwdn']) OR !empty($_SESSION['pwdn'])){
    $sessions = 1;
    include_once('login.php');
    }else{
    include_once('loginform.php');
    }
    
include_once('style/footer.php');
?>