<?php
// start silent session
@session_start();

include_once('style/header.php');

// php not to old?

if (version_compare(PHP_VERSION, '5.1.0') !== 1) {
     echo '<b>Your php-Version is too old - please update at least to 5.1!</b>';
     exit;
}


// actiove session?
if(isset($_SESSION['pwdn']) OR !empty($_SESSION['pwdn'])){
    $sessions = 1;
    include_once('login.php');
    }else{
    include_once('loginform.php');
    }

include_once('style/footer.php');

?>