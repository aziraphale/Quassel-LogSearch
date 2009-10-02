<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

// start silent session
if (session_id() == ""){
    @session_start();
}

include_once('style/header.php');

// php not to old?
if (version_compare(PHP_VERSION, '5.1.0') !== 1) {  // 5.1 ist notwenig wegen OOP
     die('<b>Your php-Version is too old - please update at least to 5.1!</b>');
}


// active session or login-try?
if(isset($_SESSION['pwdn']) OR !empty($_SESSION['pwdn'])){
    $sessions = 1;
    include_once('login.php');
    }elseif($_REQUEST['login']==true){
            include_once('login.php');
            }else{
                include_once('loginform.php');
                }

include_once('style/footer.php');

?>