<?php
// search.class

class searchengine
{

function getmicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

function search($bufferid, $input,$number){

     $Anfangszeit = $this->getmicrotime();

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
        $result = pg_prepare($dbconn, "my_query", 'SELECT * FROM backlog WHERE "type" = 1 AND bufferid = $1 '. $input_string .' order by messageid DESC limit ' . $number);
        $result = pg_prepare($dbconn, "sender", 'SELECT sender FROM sender WHERE senderid = $1');
        $i=0;
        $result = pg_execute($dbconn, "my_query", $search_zeug);

        // summer || winter ?
         if(date("I")){
            $addtime = 3600*2;
            }else{
                $addtime = 3600;
                }

        while($search_ary = pg_fetch_array($result)) {

         $db_qry = pg_execute($dbconn, "sender", array($search_ary["senderid"]));

           $user = explode ( '!', pg_fetch_result ($db_qry, 0, 0) );
           $output .= '<div class="wrap" id="d'. $search_ary[0] .'"><span onclick="moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\');">#&nbsp;</span><font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']</font>&nbsp;<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]) . '</div><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;">Loading...</div>';
           $i++; 
            }
            if($i== 0){
                $output .=  '<center>No results found for "'.$input.'" ...</center>';}

    $Endzeit = $this->getmicrotime();
    $output .= '<br><br><div style="font-size:6pt;text-align:center;">'.$i.' results in ' . number_format($Endzeit-$Anfangszeit, 4, ",", ".") . ' seconds.</div>';

  return $output;
  } 
}

?>