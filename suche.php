<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

require_once('debuger.php');

    if(isset($_REQUEST['search'])){
         $search = $_REQUEST['search'];
         }

    if(isset($_REQUEST['searchid'])){
         $searchid = $_REQUEST['searchid'];
         }else{
            $searchid = 0;
            }

    if((!isset($userid)) or empty($userid)){
         if(isset($_REQUEST['userid'])){
         $userid = intval($_REQUEST['userid']);
         }}

// started searching?          
if((!isset($search)) or empty($search)){

    if((isset($mobile) OR isset($_SESSION['mobile'])) AND ($mobile == TRUE)){
        include('searchhead.mobile.php');
        }else{
            include('searchhead.php');}

}else{
     $input = stripslashes($_REQUEST['string']);
     $bufferid = $_REQUEST['buffername'];
     $number = intval($_REQUEST['number']);
     $time_start = trim($_REQUEST['time_start']);
     $time_end = trim($_REQUEST['time_end']);
     $regex = $_REQUEST['regexid'];
     $types = $_REQUEST['types'];
     $sorting = $_REQUEST['sorting'];
    require_once('classes/search.class.php');
    $search=new searchengine(); 
if($types == 'true'){
    $types = 0;
    }else{
        $types = 1;
        }
if($sorting == 'true'){
    $sorting = 1;
    $sortingpic = 'view-sort-descending.png';
    }else{
        $sorting = 0;
        $sortingpic = 'view-sort-ascending.png';
        }        

if($searchid == 0){
    echo '<span id="searchid" style="display:none;">0</span><span onclick="such_more('.$sorting.');" style="position:absolute; right:5px;top:152px; z-index:9;" title="'._('Search further!').'"><img id="morelink" src="style/archive-insert.png"></span><span onclick="sorting();" title="'._('Change sorting-direction').'" style="position:absolute; right:3px;top:125px; z-index:9;"><img id="sortlink" src="style/'.$sortingpic.'"></span>';
    }

        echo '<script language="javascript" type="text/javascript">',
             'document.getElementById(\'searchid\').innerHTML=',
             intval($searchid)+1,
             '</script>';
             
    echo '<div id="innersearch">';
    echo $search->search($searchid,$bufferid, $input,$number,$time_start,$time_end,$regex,$types,$sorting);
    echo '</div>';
}
?>