<?php
     $search = $_REQUEST['search'];
     $input = $_REQUEST['string'];
     $bufferid = $_REQUEST['buffername'];
     $number = intval($_REQUEST['number']);
     if((!isset($userid)) or empty($userid)){    
         $userid = intval($_REQUEST['userid']);
         } 
     if((!isset($search)) or empty($search)){
        
        include('searchhead.php');

}else{

function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$Anfangszeit = getmicrotime();

require_once('classes/search.class.php');
$search=new searchengine(); 
   
    
    echo $search->search($bufferid, $input,$number);
   
    
$Endzeit = getmicrotime();
echo '<br><br><div style="font-size:6pt;text-align:center;">'.$i.' results in ',
     number_format($Endzeit-$Anfangszeit, 4, ",", "."),
     ' seconds.</div>';}
?>    