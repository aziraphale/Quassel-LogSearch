<?php
//
//      Quassel Backlog Search - classes
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

// backend-class for sqlite support
require_once('classes/parser.class.php');
class backend extends parser {

function login(){
    require("config.php");

        if(!class_exists('PDO')){ // no pdo no sqlite3
            echo _('<b>Could not connect to database!<br>Could not find PDO Driver for SQLite 3.x!<br>Please check your PHP and dependencies</b>');
            exit();
            }
  
    try {
    $conn = new PDO("sqlite:$sqlitedb");
    }
    catch(PDOException $e) {    // getMessage (human readable)
        echo _('<b>Could not connect to database!<br>&nbsp;&nbsp;&nbsp;PHP-error: '.$e->getMessage().'!<br>Please edit/check your config.php - wrong backend chosen or wrong database-path - and check dependencies!</b>');
        exit;
    }
    return $conn;
    }

function search_backend($input_string,$time_string,$search_zeug,$number,$type=0,$sorting=0,$ssary){
    $output = NULL;
    $search_ary = '';
    $dbconn = $this->login();

        $timeary = explode ('||',$time_string);
        $time_string ='';        
        if(!empty($timeary[0])){
            $time_string = ' AND time > "' .strtotime($timeary[0]).'"';
        }       

        if(!empty($timeary[1])){
            $time_string .= ' AND time < "' .strtotime($timeary[1]).'"';
        }
    $ssstring = NULL;
    //nickssearch
        if($ssary != NULL){
            foreach($ssary as $sendernick){
                $db_qry = $dbconn->query('SELECT senderid FROM sender WHERE sender LIKE "'. $sendernick.'"');
                foreach($db_qry as $zwary) {
                    $ssstring[] = $zwary[0];
                    }
                }
            $ssstring = ' AND senderid IN ('.implode(',',$ssstring).')';
            }

        $buffers = array_shift($search_zeug);
        $result = $dbconn->query('SELECT * FROM backlog WHERE ("type" = 1 OR  "type" = 4) AND bufferid IN ('.$buffers.') '. $input_string .$time_string.$ssstring. ' order by messageid DESC limit ' . $number);
        $i=0;

        foreach($result as $search_ary) {

           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
           $user = explode ( '!',$user);
           $search_ary['time'] = date("r",$search_ary['time']); // timeworkaround
           $output[] = $this->parse($search_ary,$user,$type,0,0,$sorting);    //parse everything
           $i++; 
           }

    if($sorting == 1){
        $output = array_reverse($output);}
        
    $outputary[0] = implode('',$output);
    $outputary[1] = $i;
    if(!count($search_ary)){
        $outputary[2] = $search_ary['type'];
        }

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
    // get bufferids and buffernames for user
   $result = $dbconn->query("SELECT buffername,bufferid,networkid FROM buffer WHERE userid = $userid AND buffername!='' order by networkid ASC,buffername ASC;");

    foreach($result as $search_ary) {
        $array[] = $search_ary[0] .'||'. $search_ary[1].'||'. $search_ary[2];
        }
    return $array;
    $dbconn = NULL;
    }


function networkname($networkid){
   $dbconn = $this->login();
    // get networknames
   $result = $dbconn->query("SELECT networkname FROM network WHERE networkid = '$networkid';");

    foreach($result as $search_ary) {
        $array = $search_ary[0];
        }
    return $array;
    $dbconn = NULL;
    }


function buffername($bufferid){
   $dbconn = $this->login();
    // get buffernames
   $result = $dbconn->query("SELECT buffername,networkid FROM buffer WHERE bufferid = '$bufferid';");

    foreach($result as $search_ary) {
        $array[0] = $search_ary[0];
        $array[1] = $search_ary[1];
        }
    return $this->networkname($array[1]) .' -> '. $array[0];
    $dbconn = NULL;
    }


function moreinfo($bufferid,$messageid,$types=0,$sorting=0){
        $output = '';
        $output1 =NULL;
        $downid = '';
        //timezone support
        require('config.php');
             // summer || winter ?
             if(date('I')){
                $addtime = 36*($timezone+100);
                }else{
                    $addtime = 36*$timezone;
                    }
    $dbconn = $this->login();

    $result = $dbconn->query("SELECT * FROM backlog WHERE  (type = 1 OR  type = 4) AND bufferid = $bufferid AND messageid >= $messageid order by messageid ASC limit 9");

    foreach($result as $search_ary) {
        $array[] = $search_ary;
            }

    $array = array_reverse($array);
        $i=count($array);
    foreach($array as $search_ary){
        if($i==count($array)){
            $output .= '<span style="display:none;" id="up'.$messageid.'">'.$search_ary["messageid"].'</span>'; // more up
            }
        $i--;
        if($i==0){
            $hl=1;
            }else{
                $hl = NULL;}
           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
            $user = explode ( '!',$user);
           $search_ary["time"] = date("r",$search_ary["time"]); // timeworkaround
           $output1[] = $this->parse($search_ary,$user,$types,1,$hl);
        }

        $result = $dbconn->query("SELECT * FROM backlog WHERE (type = 1 OR  type = 4) AND bufferid = $bufferid AND messageid < $messageid order by messageid DESC limit 8");
    foreach($result as $search_ary) {
           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
            $user = explode ( '!',$user);
            $search_ary["time"] = date("r",$search_ary["time"]); // timeworkaround
            $output1[] = $this->parse($search_ary,$user,$types,1);
            $downid = $search_ary["messageid"];
    }
    if($sorting == 1){
        $output1 = array_reverse($output1);}
        $output1 = implode('',$output1);
    $output .= $output1.'<span style="display:none;" id="down'.$messageid.'">'.$downid.'</span>'; // more down
    return $output;
    $dbconn = NULL;
    }

function moremore($bufferid,$messageid,$state,$types=0,$sorting=0){
        $output = '';
        $lastid = '';
        //timezone support
        require('config.php');
             // summer || winter ?
             if(date('I')){
                $addtime = 36*($timezone+100);
                }else{
                    $addtime = 36*$timezone;
                    }

    $dbconn = $this->login();

    if($state=='up'){   // if want newer
    
    $result = $dbconn->query("SELECT * FROM backlog WHERE  (type = 1 OR  type = 4) AND bufferid = $bufferid AND messageid > $messageid order by messageid ASC limit 9");
   
    if(empty($result)){
        die;}
    foreach($result as $search_ary) {
        $array[] = $search_ary;
            }
    if(empty($array)){
        die;}

    $array = array_reverse($array);
        $i=count($array);
    foreach($array as $search_ary){
        if($i==count($array)){
            $lastid = $search_ary["messageid"]; // more up
            }
        $i--;
           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
            $user = explode ( '!',$user);
           $search_ary["time"] = date("r",$search_ary["time"]); // timeworkaround
           $output[] = $this->parse($search_ary,$user,$types,1);
        }}else{ // else want older

$result = $dbconn->query("SELECT * FROM backlog WHERE (type = 1 OR  type = 4) AND bufferid = $bufferid AND messageid < $messageid order by messageid DESC limit 9");
    if(empty($result)){
        die;}

    foreach($result as $search_ary) {
           $result2 = $dbconn->query('SELECT sender FROM sender WHERE senderid = '. $search_ary['senderid']);
           foreach($result2 as $search_ary2) {
                $user = $search_ary2[0];
                }
            $user = explode ( '!',$user);
            $search_ary["time"] = date("r",$search_ary["time"]); // timeworkaround
            $output[] = $this->parse($search_ary,$user,$types,1);
    $lastid = $search_ary["messageid"]; //more down
    }


            }
    
    return array($output,$lastid);
    $dbconn = NULL;
    }


    }