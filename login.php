<?php
@session_start();

// active session?
if($sessions == 1){
    $usern = $_SESSION['usern']; 
    $pwdn = $_SESSION['pwdn'];    
}else{
    $usern = $_REQUEST['quasseluser'];
    $pwdn = sha1($_REQUEST['quasselpwd']);
}

// errorhandling (vllt n bissl spät)
    if(is_file('config.php')){  // gibts die config?
    require('config.php');        
        }else{
            echo '<b>There is no config.php - please reinstall Quassel Backlog Search!</b><br>For help read install and readme notes!';
            exit;
            }
    if(is_file('classes/'.$backend.'.class.php')){  // steht ein existierendes backend in der config?
            require_once('classes/'.$backend.'.class.php');
        }else{
            echo '<b>Invalid backend chosen - please edit your config.php!</b><br>For help read install and readme notes!';
            exit;
            }

    $backend=new backend();

    $userid = $backend->login_backend($usern,$pwdn);
$userid = intval($userid);
// userid = valid?
if($userid == 0){
    $error='<b>Username and Password do not match!</b><br>';
    include('loginform.php');
        }else{
    $_SESSION['usern'] = $usern;
    $_SESSION['pwdn'] = $pwdn;

    include('suche.php');
    echo '<br><span id="footer">developed by <a href="http://m4yer.minad.de/?page=5" target="_blank" style="color:#33333">m4yer</a> 2009;</span><br><br><br>';
}

?>