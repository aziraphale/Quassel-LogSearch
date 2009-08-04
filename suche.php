<?php
     $search = $_REQUEST['search'];
     $input = $_REQUEST['string'];
     $bufferid = $_REQUEST['buffername'];
     $number = intval($_REQUEST['number']);
     if((!isset($userid)) or empty($userid)){    
         $userid = intval($_REQUEST['userid']);
         } 
     if((!isset($search)) or empty($search)){
        
        include('searchhead.php');

}else{

function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$Anfangszeit = getmicrotime();

require_once("config.php");
$dbconn = pg_connect ("dbname=$dbname user=$user  password='$password' port=$port host=$host");

$search_zeug[] = $bufferid;

$input_array = explode(" ",$input);
$i=2;
foreach($input_array AS $sonstwas){
    $input_string  .= 'AND lower(message) ILIKE $'. $i;
    $search_zeug[] = '%'.$sonstwas.'%';
    $i++;
}
$result = pg_prepare($dbconn, "my_query", 'SELECT * FROM backlog WHERE "type" = 1 AND bufferid = $1 '. $input_string .'order by messageid DESC limit ' . $number);
$result = pg_prepare($dbconn, "sender", 'SELECT sender FROM sender WHERE senderid = $1');
$i=0;
$result = pg_execute($dbconn, "my_query", $search_zeug);


 if(date("I")){
    $addtime = 3600*2;
    }else{
        $addtime = 3600;
        }
 
while($search_ary = pg_fetch_array($result)) {
 
 $db_qry = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

   $user = explode ( '!', pg_fetch_result ($db_qry, 0, 0) );
   echo '<div class="wrap" id="d'. $search_ary[0] .'"><span onclick="moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\');">#&nbsp;</span><font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']</font>&nbsp;<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]) . '</div><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;">Loading...</div>';
   $i++; 
    }
    if($i== 0){
        echo '<center>Suche ergebnislos ...</center>';}
    
   
    
$Endzeit = getmicrotime();
echo '<br><br><div style="font-size:6pt;text-align:center;">Das Suchen dieser '.$i.' Ergebnisse hat ',
     number_format($Endzeit-$Anfangszeit, 4, ",", "."),
     ' Sekunden gedauert.</div>';}
?>    