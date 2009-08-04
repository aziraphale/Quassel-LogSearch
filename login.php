<?php
@session_start();

// actiove session?
if($sessions == 1){
    $usern = $_SESSION['usern']; 
    $pwdn = $_SESSION['pwdn'];    
}else{
    $usern = $_REQUEST['quasseluser'];
    $pwdn = sha1($_REQUEST['quasselpwd']);
} 

require_once('config.php');
$dbconn = pg_connect ("dbname=$dbname user=$user password='$password' port=$port host=$host") or die("Connection to PostgreSQL failed.");

// login
$db_qry = pg_query($dbconn,"SELECT userid FROM quasseluser WHERE username = '$usern' AND password = '$pwdn';");
$userid = @pg_fetch_result ($db_qry, 0, 0);
$userid = intval($userid);
// userid = valid?
if($userid == 0){
    $error='<b>Username and password do not match!</b><br>';
    include('loginform.php');
        }else{
    $_SESSION['usern'] = $usern; 
    $_SESSION['pwdn'] = $pwdn; 

    include('suche.php');
    echo '<br><span id="footer">developed by <a href="http://m4yer.minad.de/?page=5" target="_blank" style="color:#33333">m4yer</a> 2009;</span><br><br><br>';
}

?>