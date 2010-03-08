<?php
//
//      Quassel Backlog Search
//      developed 2009-2010 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
    
    if(isset($_REQUEST['time'])){
         $time = $_REQUEST['time'];
         }else{
            echo 1;
            exit();
            }

    if(strtotime($time) === false){
        echo 0;
        }else{
            echo 1;
            }
?>