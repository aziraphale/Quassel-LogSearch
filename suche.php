<?php

     $search = $_REQUEST['search'];
     $input = $_REQUEST['string'];
     $bufferid = $_REQUEST['buffername'];
     $number = intval($_REQUEST['number']);
     if((!isset($userid)) or empty($userid)){    
         $userid = intval($_REQUEST['userid']);
         }

// startet searching?          
if((!isset($search)) or empty($search)){

    include('searchhead.php');

}else{

    require_once('classes/search.class.php');
    $search=new searchengine(); 

echo $search->search($bufferid, $input,$number);

}
?>    