<?php

     $search = $_REQUEST['search'];
     if((!isset($userid)) or empty($userid)){    
         $userid = intval($_REQUEST['userid']);
         }

// startet searching?          
if((!isset($search)) or empty($search)){

    include('searchhead.php');

}else{
     $input = $_REQUEST['string'];
     $bufferid = $_REQUEST['buffername'];
     $number = intval($_REQUEST['number']);
     $time_start = trim($_REQUEST['time_start']);
     $time_end = trim($_REQUEST['time_end']);
    require_once('classes/search.class.php');
    $search=new searchengine(); 

echo $search->search($bufferid, $input,$number,$time_start,$time_end);

}
?>    