<?php
//
//      Quassel Backlog Search
//      developed 2009-2010 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
require_once('classes/gettext.class.php');
require_once('debuger.php');
if (session_id() == ""){
    @session_start();
}


// active session?
if(!isset($sessions) or empty($sessions)){
    $sessions = 0;
    }

if($sessions == 1){
    $usern = $_SESSION['usern']; 
    $pwdn = $_SESSION['pwdn'];
}elseif($sessions == 2){
    $cookieary = explode(':',$_COOKIE['login']);
    $usern = $cookieary[0]; 
    $pwdn = $cookieary[1];       
}else{
    $usern = $_REQUEST['quasseluser'];
    $pwdn = sha1($_REQUEST['quasselpwd']);
    if(isset($_REQUEST['cookie'])){
        $cookie = $_REQUEST['cookie'];
        }
}

    $backend=new backend();

    $userid = $backend->login_backend($usern,$pwdn);
    $userid = intval($userid);
// userid = valid?
if($userid == 0){
    $error=_('<b>Username and Password do not match!</b><br>');
    include('loginform.php');
        }else{
    $_SESSION['usern'] = $usern;
    $_SESSION['pwdn'] = $pwdn;
    $loggedin = TRUE;
    
    // set and update cookie
    if(isset($cookie) OR $sessions == 2){
        setcookie('login', $usern.':'.$pwdn, time()+3600*24*7);
        }
    
    include('suche.php');

    echo '<br><span id="footer">'._('developed by').' <a href="http://m4yer.minad.de/?page=5" target="_blank" style="color:#33333">m4yer</a> 2009-2010</span><br>';
}

?>