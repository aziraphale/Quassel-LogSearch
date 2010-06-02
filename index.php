<?php
//
//      Quassel Backlog Search
//      developed 2009-2010 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

// start silent session
if (session_id() == ""){
    @session_start();
}

// errorhandling
    // php not to old?
    if (version_compare(PHP_VERSION, $required_php_version) !== 1) {  // 5.2 ist notwenig wegen OOP
         die(_('<b>Your <u>php-Version</u> is too old - please update at least to '.$required_php_version.'!</b>'));
    }
    if(is_file('config.php')){  // gibts die config?
    require('config.php');
        }else{
            echo _('<b>There is no config.php - please reinstall Quassel Backlog Search!</b><br>For help read install and readme notes!');
            exit;
            }
    if(is_file('classes/'.$backend.'.class.php')){  // steht ein existierendes backend in der config?
            require_once('classes/'.$backend.'.class.php');
        }else{
            echo _('<b>Invalid backend chosen - please edit your config.php!</b><br>For help read install and readme notes!');
            exit;
            }
    if (version_compare($config_version, $config_version2) < 0) {
         die(_('<b>Your <u>config-Version</u> is too old - please update your config.php!</b><br>Take a look at config.php.sample what changed! (Unix-hint: diff)'));
    }

// most things should be fine now
require_once('debuger.php');

// mobile-redirection
    $mobile = FALSE;
    // mobile umleitung
    if(preg_match('/(IEMobile|Windows CE|NetFront|PlayStation|PLAYSTATION|like Mac OS X|MIDP|UP\.Browser|Symbian|Nintendo|Pocket|Mobile|Portable|Smartphone|SDA|PDA|Handheld|WAP|Palm|Avantgo|cHTML|BlackBerry|Opera\.Mini|Nokia)/', $_SERVER['HTTP_USER_AGENT'])){
        $mobile = TRUE;
        $_SESSION['mobile'] = TRUE;
        }
    
    // make sure both vars are set.
    if((isset($_SESSION['mobile'])) AND $_SESSION['mobile'] == TRUE){
        $mobile = TRUE;
        }else{
            $mobile = FALSE;
            }
    
    // force?
    if((isset($_REQUEST['force_standard'])) AND $_REQUEST['force_standard'] == TRUE){
        $mobile = FALSE;
        $_SESSION['mobile'] = FALSE;
        }elseif((isset($_REQUEST['force_mobile'])) AND $_REQUEST['force_mobile'] == TRUE){
        $mobile = TRUE;
        $_SESSION['mobile'] = TRUE;
        }


// show design if everything is ready
ob_start(); 


//
//  build design
//

// mobile header
if($mobile){
    include_once('style/header.mobile.php');
    }else{
        include_once('style/header.php');
        }


// active session or login-try or none?
if(isset($_SESSION['pwdn']) OR !empty($_SESSION['pwdn'])){
    $sessions = 1;
    include_once('login.php');
    }elseif(isset($_COOKIE['login']) OR !empty($_COOKIE['login'])){
        $sessions = 2;
        include_once('login.php');
        }elseif((isset($_REQUEST['login'])) AND $_REQUEST['login'] == TRUE){
                include_once('login.php');
                }else{
                    include_once('loginform.php');
                    }

// mobile footer
if($mobile){
    include_once('style/footer.mobile.php');
    }else{
        include_once('style/footer.php');
        }

// now show design       
ob_end_flush(); 

?>