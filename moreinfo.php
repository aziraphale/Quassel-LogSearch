<?php
$bufferid = $_REQUEST['bufferid'];
$messageid = $_REQUEST['messageid'];

?>
<div style="margin:5px;"><span onclick="document.getElementById('m<?=$messageid?>').style.display = 'none'; document.getElementById('d<?=$messageid?>').style.display = 'block';">#</span>
<?php
require_once("config.php");
$dbconn = pg_connect ("dbname=$dbname user=$user port=$port host=$host");


$result = pg_query($dbconn,"SELECT * FROM backlog WHERE type = 1 AND bufferid = $bufferid AND messageid >= $messageid order by messageid ASC limit 9");
$result2 = pg_prepare($dbconn, "sender", 'SELECT sender FROM sender WHERE senderid = $1');

while($search_ary = pg_fetch_array($result)){
$array[] = $search_ary;
    }

$array = array_reverse($array);

foreach($array as $search_ary){
     $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

       $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
       echo '<font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']</font>&nbsp;<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]) . '<br>';
    $result = pg_query($dbconn,"SELECT * FROM backlog WHERE type = 1 AND bufferid = $bufferid AND messageid < $messageid order by messageid DESC limit 8");
    }


while($search_ary = pg_fetch_array($result)){
 $db_qry2 = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

   $user = explode ( '!', pg_fetch_result ($db_qry2, 0, 0) );
   echo '<font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']</font>&nbsp;<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]) . '<br>';
    }

?></div>