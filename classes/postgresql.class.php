<?php
//
//      Quassel Backlog Search - classes
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

// backend-class for postgresql support
require_once('classes/parser.class.php');
class backend extends parser{

function login(){
    require("config.php");
    if(!function_exists('pg_connect')){ // no connect no postgres
        echo '<b>Could not connect to database!<br>Could not find PostgreSQL Support (pgsql)!<br>Please check your PHP and install PostgreSQL Support (pgsql)</b>';
        exit();
        }

    $conn = @pg_connect ("dbname=$dbname user=$user password='$password' port=$port host=$host"); // suppress error and parse later
    
    if($conn === false){    // parse error human readable
        echo '<b>Could not connect to database!<br>Connection failed!<br>Please edit/check your config.php - wrong backend chosen or wrong database-infos!</b>';
        exit;
        }else{
            return $conn;
            }
    }

function search_backend($input_string,$time_string,$search_zeug,$number,$type=0,$sorting=0){   
    $dbconn = $this->login();
        $output = NULL;
        
        if($type == 0){ // choose type
            $type_string = ' AND "type" IN (1,4)';
            }else{
                $type_string = ' AND "type" IN (1,4,8,32,64,128,256,512,1024,16384)';
                }
        $buffers = array_shift($search_zeug);
    //prepare and execute search
        $result = pg_prepare($dbconn, 'my_query', 'SELECT * FROM backlog WHERE bufferid IN ('.$buffers.') '. $type_string. $input_string . $time_string .' order by messageid DESC limit ' . $number);
        $result = pg_prepare($dbconn, 'sender', 'SELECT sender FROM sender WHERE senderid = $1');
        $i=0;
        $result = @pg_execute($dbconn, 'my_query', $search_zeug);
            if(empty($result)){ // leer
                echo '<center>Invalid request.</center>';
                }

            
        while($search_ary = @pg_fetch_array($result)) { // jede zeile parsen und sender bestimmen
           $db_qry = @pg_execute($dbconn, 'sender', array($search_ary['senderid']));
           $user = explode ( '!', @pg_fetch_result ($db_qry, 0, 0) );
           $output[] = $this->parse($search_ary,$user,$type);    //parse everything.
           $i++; 
            }
    
    if($sorting == 1){
        $output = array_reverse($output);}
        
    $output = implode('',$output);
    
    $outputary[0] = $output;
    $outputary[1] = $i;


    return $outputary;
    pg_close($dbconn);
    }    


function login_backend($usern,$pwdn){
    $dbconn = $this->login();

    // login
    $db_qry = pg_query($dbconn,"SELECT userid FROM quasseluser WHERE username = '$usern' AND password = '$pwdn';");

    if(pg_num_rows($db_qry)==0){
            return FALSE;
        }else{
            return @pg_fetch_result ($db_qry, 0, 0);}
    pg_close($dbconn);
    }


function bufferids($userid){
   $dbconn = $this->login();
    // get bufferids und buffernames for user 
   $result = pg_query($dbconn,"SELECT buffername,bufferid,networkid FROM buffer WHERE userid = $userid AND buffername!='' order by networkid ASC,buffername ASC");

    while($search_ary = pg_fetch_array($result)) {
        $array[] = $search_ary[0] .'||'. $search_ary[1].'||'.$search_ary[2];
         }

    return $array;
    pg_close($dbconn);
    }

function networkname($networkid){
    $dbconn = $this->login();
    $db_qry = pg_query($dbconn,"SELECT networkname FROM network WHERE networkid = '$networkid';");    
    return @pg_fetch_result ($db_qry, 0, 0);
    }

function buffername($bufferid){
    $dbconn = $this->login();
    $db_qry = pg_query($dbconn,"SELECT buffername,networkid FROM buffer WHERE bufferid = '$bufferid';");
    return $this->networkname(@pg_fetch_result ($db_qry, 0, 1)) .' -> '. @pg_fetch_result ($db_qry, 0, 0) ;
    }


function moreinfo($bufferid,$messageid,$types){
        $output = '';
        //timezone support
        require('config.php');
             // summer || winter ?
             if(date('I')){
                $addtime = 36*($timezone+100);
                }else{
                    $addtime = 36*$timezone;
                    }

            if($types == 0){    // choose type
            $type_string = ' AND "type" IN (1,4)';
            }else{
                $type_string = ' AND "type" IN (1,4,8,32,64,128,256,512,1024,16384)';
                }
    
    $dbconn = $this->login();

    $result = pg_query($dbconn,"SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid >= $messageid order by messageid ASC limit 9");
    $result2 = pg_prepare($dbconn, "sender", 'SELECT sender FROM sender WHERE senderid = $1');
    
    while($search_ary = pg_fetch_array($result)){
        $array[] = $search_ary;
            }

    $array = array_reverse($array);
    $i=count($array);
    foreach($array as $search_ary){
        if($i==count($array)){
            $output .= '<span style="display:none;" id="up'.$messageid.'">'.$search_ary["messageid"].'</span>'; // more up
            }
        $i--;
        if($i == 0){$hl = 1;
            }else{
                $hl = 0;}
         $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

           $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
           $output .= $this->parse($search_ary,$user,$types,1,$hl);   //parse
        }
        
        
    $result = pg_query($dbconn,"SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid < $messageid order by messageid DESC limit 8");

    while($search_ary = pg_fetch_array($result)){
         $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

           $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
           $output .= $this->parse($search_ary,$user,$types,1);   //parse
           $downid = $search_ary["messageid"];
    }
    $output .= '<span style="display:none;" id="down'.$messageid.'">'.$downid.'</span>'; // more down
    return $output;
    pg_close($dbconn);
    }

function moremore($bufferid,$messageid,$state,$types){
        $output = '';
        $lastid = '';
        if(empty($messageid)){
            die();
            }
        //timezone support
        require('config.php');
             // summer || winter ?
             if(date('I')){
                $addtime = 36*($timezone+100);
                }else{
                    $addtime = 36*$timezone;
                    }
            if($types == 0){    // choose type
            $type_string = ' AND "type" IN (1,4)';
            }else{
                $type_string = ' AND "type" IN (1,4,8,32,64,128,256,512,1024,16384)';
                }
    $dbconn = $this->login();
    $result2 = pg_prepare($dbconn, "sender", 'SELECT sender FROM sender WHERE senderid = $1');

    if($state=='up'){   // if want newer
    
    $result = pg_query($dbconn,"SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid > $messageid order by messageid ASC limit 9");
    $array = array();
    while($search_ary = pg_fetch_array($result)){
        $array[] = $search_ary;
            }
    if(count($array)==0){
        die;
        }

    $array = @array_reverse($array);
    $i=count($array);
    foreach($array as $search_ary){
        if($i==count($array)){
            $lastid = $search_ary["messageid"]; // more up
            }
        $i--;
         $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

           $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
           $output .= $this->parse($search_ary,$user,$types,1);   //parse
        }}else{ // else want older

                $result = @pg_query($dbconn,"SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid < $messageid order by messageid DESC limit 9");

            while($search_ary = @pg_fetch_array($result)){
                   $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));
        
                   $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
                   $output .= $this->parse($search_ary,$user,$types,1);   //parse
                   $lastid = $search_ary["messageid"];
            }}
    
    return array($output,$lastid);
    pg_close($dbconn);
    }


    }