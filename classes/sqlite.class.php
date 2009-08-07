<?php

class backend{

function login(){
    require("config.php");
  
    try {
    $conn = new PDO("sqlite:$sqlitedb");
    }
    catch(PDOException $e) {
        echo '<b>Could not connect to database!<br>Please edit your config.php - wrong backend chosen or wrong database-path!</b>';
        exit;
    }
    return $conn;
            
    }

function search_backend($input_string,$time_string,$search_zeug,$number){   
    $dbconn = $this->login();
       
        $timeary = explode ('||',$time_string);
        $time_string ='';        
        if(!empty($timeary[0]) AND $timeary[0] != "Starttime"){
            $time_string = ' AND time > "' .strtotime($timeary[0]).'"';
        }       

        if(!empty($timeary[1]) AND $timeary[1] != "Endtime"){
            $time_string .= ' AND time < "' .strtotime($timeary[1]).'"';
        }
      
        $result = $dbconn->query('SELECT * FROM backlog WHERE "type" = 1 AND bufferid = '.$search_zeug[0].' '. $input_string .$time_string. ' order by messageid DESC limit ' . $number);
        $i=0;

        foreach($result as $search_ary) {

    
           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
            $user = explode ( '!',$user);
           
           $output .= '<div class="wrap" id="d'. $search_ary[0] .'"><span onclick="moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\');">#&nbsp;</span><font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +$search_ary["time"]).']</font>&nbsp;<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]) . '</div><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;">Loading...</div>';
           $i++; 
            }
    
    $outputary[0] = $output;
    $outputary[1] = $i;

    return $outputary;
    $dbconn = NULL;
    }    


function login_backend($usern,$pwdn){
    $dbconn = $this->login();

    // login
    $result = $dbconn->query("SELECT userid FROM quasseluser WHERE username = '$usern' AND password = '$pwdn';");
    foreach($result as $search_ary) {
    $userid = $search_ary[0];
    }
    return $userid;
    $dbconn = NULL;
    }


function bufferids($userid){
   $dbconn = $this->login();
    // get bufferids und buffernames for user 
   $result = $dbconn->query("SELECT buffername,bufferid FROM buffer WHERE userid = $userid;");

    foreach($result as $search_ary) {
        $array[] = $search_ary[0] .'||'. $search_ary[1];
        }
        natcasesort($array);

    return $array;
    $dbconn = NULL;
    }


function moreinfo($bufferid,$messageid){
    $dbconn = $this->login();

    $result = $dbconn->query("SELECT * FROM backlog WHERE type = 1 AND bufferid = $bufferid AND messageid >= $messageid order by messageid ASC limit 9");
    
    foreach($result as $search_ary) {
        $array[] = $search_ary;
            }

    $array = array_reverse($array);

    foreach($array as $search_ary){
           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
            $user = explode ( '!',$user);
           $output .= '<font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$search_ary["time"]).']</font>&nbsp;<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]) . '<br>';
        }

        $result = $dbconn->query("SELECT * FROM backlog WHERE type = 1 AND bufferid = $bufferid AND messageid < $messageid order by messageid DESC limit 8");
    foreach($result as $search_ary) {
           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
            $user = explode ( '!',$user);
           $output .= '<font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$search_ary["time"]).']</font>&nbsp;<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]) . '<br>';
    }
    return $output;
    $dbconn = NULL;
    }

    }