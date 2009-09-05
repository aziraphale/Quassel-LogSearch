<?php
    require_once('classes/parser.class.php');
class backend extends parser{

function login(){
    require("config.php");
    $conn = @pg_connect ("dbname=$dbname user=$user password='$password' port=$port host=$host");
    
    if($conn === false){
        echo '<b>Could not connect to database!<br>Please edit your config.php - wrong backend chosen or wrong database-infos!</b>';
        exit;
        }else{
            return $conn;
            }
    }

function search_backend($input_string,$time_string,$search_zeug,$number,$type=0){   
    $dbconn = $this->login();
        
        if($type == 0){
            $type_string = ' AND "type" IN (1,4)';
            }else{
                $type_string = ' AND "type" IN (1,4,8,32,64,128,256,512,1024,16384)';
                }

    //prepare and execute search
        $result = pg_prepare($dbconn, 'my_query', 'SELECT * FROM backlog WHERE bufferid = $1 '. $type_string. $input_string . $time_string .' order by messageid DESC limit ' . $number);
        $result = pg_prepare($dbconn, 'sender', 'SELECT sender FROM sender WHERE senderid = $1');
        $i=0;
        $result = pg_execute($dbconn, 'my_query', $search_zeug);

        // summer || winter ?
         if(date('I')){
            $addtime = 3600*2;
            }else{
                $addtime = 3600;
                }

        while($search_ary = pg_fetch_array($result)) {

         $db_qry = pg_execute($dbconn, 'sender', array($search_ary['senderid']));

           $user = explode ( '!', pg_fetch_result ($db_qry, 0, 0) );
           
           $output .= $this->parse($search_ary,$user,$type);
           $i++; 
            }
    
    $outputary[0] = $output;
    $outputary[1] = $i;


    return $outputary;
    pg_close($dbconn);
    }    


function login_backend($usern,$pwdn){
    $dbconn = $this->login();

    // login
    $db_qry = pg_query($dbconn,"SELECT userid FROM quasseluser WHERE username = '$usern' AND password = '$pwdn';");
    
    return @pg_fetch_result ($db_qry, 0, 0);
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


function moreinfo($bufferid,$messageid,$types){
            if($types == 0){
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
        $i--;
        if($i==0){$hl='color:black;';}
         $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

           $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
           $output .= '<font class="date" style="color:c3c3c3;'.$hl.'">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']&nbsp;</font><font style="'.$hl.'"' . $this->parse($search_ary,$user,$types,1) . '</font><br>';
        }
        
        
    $result = pg_query($dbconn,"SELECT * FROM backlog WHERE bufferid = $bufferid $type_string AND messageid < $messageid order by messageid DESC limit 8");

    while($search_ary = pg_fetch_array($result)){
         $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

           $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
           $output .= '<font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']</font>&nbsp;' . $this->parse($search_ary,$user,$types,1) . '<br>';
    }
    return $output;
    pg_close($dbconn);
    }

    }