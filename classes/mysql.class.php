<?php
//
//      Quassel Backlog Search - classes
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
//      mysql-backend developed 2011 by kode54
//

// backend-class for mysql support
require_once('classes/parser.class.php');
class backend extends parser{

function login(){
    require("config.php");
    if(!function_exists('mysqli_connect')){ // no connect no mysql
        echo _('<b>Could not connect to database!<br>Could not find MySQL Support!<br>Please check your PHP and install MySQL Support</b>');
        exit();
        }

    $conn = mysqli_connect ($sql_host, $sql_user, $sql_password, $sql_dbname, $sql_port); // suppress error and parse later
    
    if(mysqli_connect_errno()){    // parse error human readable
        echo _('<b>Could not connect to database!<br>Connection failed!<br>Please edit/check your config.php - wrong backend chosen or wrong database-infos!</b>');
        exit;
        }else{
            $conn->set_charset( "utf8" );
            return $conn;
            }
    }

function search_backend($input_string,$time_string,$search_zeug,$number,$type=0,$sorting=0,$ssary,$searchid){
    $dbconn = $this->login();
        $output = NULL;
         $searchid = $searchid * $number;

        if($type == 0){ // choose type
            $type_string = ' AND `type` IN (1,4)';
            }else{
                $type_string = ' AND `type` IN (1,4,8,32,64,128,256,512,1024,16384)';
                }

    $ssstring = NULL;
    //nickssearch
        if($ssary != NULL){
            $stmt = $dbconn->prepare('SELECT senderid FROM sender WHERE sender LIKE ?');
            foreach($ssary as $sendernick){
                $stmt->bind_param("s", $sendernick);
                $db_qry = $stmt->execute();
                $stmt->bind_result($zwary);
                while($stmt->fetch()) {
                    $ssstring[] = $zwary;
                    }
                }
            $stmt->close();

            $ssstring = ' AND senderid IN ('.implode(',',$ssstring).')';
            }

        $buffers = array_shift($search_zeug);
    //prepare and execute search
        $stmt1 = $dbconn->prepare($query = 'SELECT * FROM backlog WHERE bufferid IN ('.$buffers.') '. $type_string. $input_string . $time_string .$ssstring.' order by messageid DESC LIMIT '.$number.' OFFSET '.$searchid);
        $stmt2 = $dbconn->prepare('SELECT sender FROM sender WHERE senderid = ?');
        $i=0;
            $param_type = "";
            $params = array();
            foreach ($search_zeug as $value) {
              if ( is_int($value) ) $param_type .= "i";
              else $param_type .= "s";
            }
            $params[] = &$stmt1;
            $params[] = $param_type;
            foreach ($search_zeug as &$value) {
              $params[] = &$value;
            }
            call_user_func_array("mysqli_stmt_bind_param", $params);
            $stmt1->execute();

        $stmt1->bind_result($messageid, $time, $bufferid, $mtype, $flags, $senderid, $message);
        $results = array();
        while($stmt1->fetch()) { // jede zeile parsen und sender bestimmen
           $search_ary = array( "messageid" => $messageid, "time" => $time, "bufferid" => $bufferid, "type" => $mtype, "flags" => $flags, "senderid" => $senderid, "message" => $message);
           $results[] = $search_ary;
        }
        $stmt1->close();
        foreach ($results as $search_ary) {
           $stmt2->bind_param("i", $search_ary['senderid']);
           $stmt2->execute();
           $stmt2->bind_result($sender);
           $stmt2->fetch();
           $user = explode ( '!', $sender );
           $output[] = $this->parse($search_ary,$user,$type,0,0,$sorting);    //parse everything.
           $i++; 
            }
    
    if($sorting == 1){
        $output = array_reverse($output);}
   
    if (is_array($output)){     
        $output = implode('',$output);}
    
    $outputary[0] = $output;
    $outputary[1] = $i;

    return $outputary;
    $dbconn->close();
    }    


function login_backend($usern,$pwdn){
    $dbconn = $this->login();

    // login
    $result = $dbconn->query("SELECT userid FROM quasseluser WHERE username = '$usern' AND password = '$pwdn';");

    if($result->num_rows==0){
            $result->close();
            return FALSE;
        }else{
            $row = $result->fetch_row();
            $ret = $row[0];
            $result->close();
            return $ret;}
    $dbconn->close();
    }


function bufferids($userid){
   $dbconn = $this->login();
    // get bufferids und buffernames for user 
   $result = $dbconn->query("SELECT buffername,bufferid,networkid FROM buffer WHERE userid = $userid AND buffername!='' order by networkid ASC,buffername COLLATE utf8_general_ci ASC");

    while($search_ary = $result->fetch_row()) {
        $array[] = $search_ary[0] .'||'. $search_ary[1].'||'.$search_ary[2];
         }

    $result->close();

    return $array;
    $dbconn->close();
    }

function networkname($networkid){
    $dbconn = $this->login();
    $result = $dbconn->query("SELECT networkname FROM network WHERE networkid = '$networkid';");    
    $row = $result->fetch_row();
    $result->close();
    $dbconn->close();
    return $row[0];
    }

function buffername($bufferid){
    $dbconn = $this->login();
    $result = $dbconn->query("SELECT buffername,networkid FROM buffer WHERE bufferid = '$bufferid';");
    $row = $result->fetch_row();
    $result->close();
    return $this->networkname($row[1]) .' -> '. $row[0];
    }


function moreinfo($bufferid,$messageid,$types,$sorting=0){
        $output = NULL;
        $output1 = NULL;
        //timezone support
        require('config.php');
             // summer || winter ?
             if(date('I')){
                $addtime = 36*($timezone+100);
                }else{
                    $addtime = 36*$timezone;
                    }

            if($types == 0){    // choose type
            $type_string = ' AND `type` IN (1,4)';
            }else{
                $type_string = ' AND `type` IN (1,4,8,32,64,128,256,512,1024,16384)';
                }
    
    $dbconn = $this->login();

    $result = $dbconn->query("SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid >= $messageid order by messageid ASC limit 9");
    $stmt = $dbconn->prepare('SELECT sender FROM sender WHERE senderid = ?');
    
    while($search_ary = $result->fetch_row()){
        $array[] = $search_ary;
            }
    $result->close();

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
         $stmt->bind_param("i", $search_ary["senderid"]);
         $stmt->execute();
         $stmt->bind_result($sender);
         $stmt->fetch();

           $user = explode ( '!', $sender );
           $output1[]= $this->parse($search_ary,$user,$types,1,$hl,$sorting);   //parse
        }
        
        
    $result = $dbconn->query("SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid < $messageid order by messageid DESC limit 8");

    $results = array();
    while($search_ary = $result->fetch_row()){
         $results[] = $search_ary;
    }
    $result->close();
    foreach($results as $search_ary) {
         $stmt->bind_param("i", $search_ary["senderid"]);
         $stmt->execute();
         $stmt->bind_result($sender);
         $stmt->fetch();

           $user = explode ( '!', $sender );
           $output1[]= $this->parse($search_ary,$user,$types,1,0,$sorting);   //parse
           $downid = $search_ary["messageid"];
    }
    $stmt->close();
        if($sorting == 1){
        $output1 = array_reverse($output1);}
        $output1 = implode('',$output1);
    $output .= $output1.'<span style="display:none;" id="down'.$messageid.'">'.$downid.'</span>'; // more down
    return $output;
    $dbconn->close();
    }

function moremore($bufferid,$messageid,$state,$types,$sorting=0){
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
            $type_string = ' AND `type` IN (1,4)';
            }else{
                $type_string = ' AND `type` IN (1,4,8,32,64,128,256,512,1024,16384)';
                }
    $dbconn = $this->login();
    $stmt = $dbconn->prepare('SELECT sender FROM sender WHERE senderid = ?');

    if($state=='up'){   // if want newer
    
    $result = $dbconn->query("SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid > $messageid order by messageid ASC limit 9");
    $array = array();
    while($search_ary = $result->fetch_row()){
        $array[] = $search_ary;
            }
    $result->close();
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
         $stmt->bind_param('i', $search_ary["senderid"]);
         $stmt->execute();
         $stmt->bind_result($sender);
         $stmt->fetch();

           $user = explode ( '!', $sender );
           $output[] = $this->parse($search_ary,$user,$types,1);   //parse
        }}else{ // else want older

                $result = $dbconn->query("SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid < $messageid order by messageid DESC limit 9");

            $results = array();
            while($search_ary = $result->fetch_row()){
                   $results[] = $search_ary;
            }
            $result->close();
            foreach($results as $search_ary) {
                   $stmt->bind_param("i", $search_ary["senderid"]);
                   $stmt->execute();
                   $stmt->bind_result($sender);
        
                   $user = explode ( '!', $sender );
                   $output[] = $this->parse($search_ary,$user,$types,1);   //parse
                   $lastid = $search_ary["messageid"];
            }}

    $stmt->close();

    return array($output,$lastid);
    $dbconn->close();
    }


    }
