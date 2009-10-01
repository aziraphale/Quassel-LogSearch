<?php
require_once('debuger.php');
     $search = $_REQUEST['search'];
     if((!isset($userid)) or empty($userid)){    
         $userid = intval($_REQUEST['userid']);
         }

// startet searching?          
if((!isset($search)) or empty($search)){

    include('searchhead.php');

}else{
     $input = stripslashes($_REQUEST['string']);
     $bufferid = $_REQUEST['buffername'];
     $number = intval($_REQUEST['number']);
     $time_start = trim($_REQUEST['time_start']);
     $time_end = trim($_REQUEST['time_end']);
     $regex = $_REQUEST['regexid'];
     $types = $_REQUEST['types'];
    require_once('classes/search.class.php');
    $search=new searchengine(); 
if($types == 'true'){
    $types = 0;
    }else{
        $types = 1;
        }
        

echo $search->search($bufferid, $input,$number,$time_start,$time_end,$regex,$types);

}
?>    