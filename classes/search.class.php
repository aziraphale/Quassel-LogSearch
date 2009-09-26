<?php
// search.class
    require("config.php");
    require_once('parser.class.php');
    require_once($backend.'.class.php');

class searchengine extends backend
{

function getmicrotime(){
    //suchzeit berechnen
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

function search($bufferid, $input,$number,$time_start,$time_end,$regex=0,$types=1){
    require("config.php");
     $Anfangszeit = $this->getmicrotime();
    if(empty($bufferid)){
            echo '<center>No results - please select a Chat.</center>';
            $Endzeit = $this->getmicrotime();
            echo '<br><br><div style="font-size:6pt;text-align:center;">No results in ' . number_format($Endzeit-$Anfangszeit, 4, ",", ".") . ' seconds.</div>';
            die(1);
        }

        //prepare vars
        $search_zeug[] = $bufferid;


        //regex braucht kein externes or, wer regex nutz, soll auch das or so machen ;)
        if($regex != 'true'){
             $method = 'ILIKE';
            $input_array = explode(' ',$input);
            $i=1;
            foreach($input_array AS $sonstwas){
                $input_string  .= 'AND lower(message) '.$method.' $'. $i;
                $search_zeug[] = '%'.$sonstwas.'%';
                $i++;
            }
         }else{ // regex
            $input_string  .= 'AND message ~* $1';
            $search_zeug[] = $input;
            }
        
        // zeitspannensuche?
        if(!empty($time_start) AND $time_start != "Starttime"){
            $time_string .= ' AND time > $'.$i.'AT TIME ZONE \'UTC\'';
            $i++;
            $search_zeug[] = date('Y-m-d H:i:s',strtotime($time_start));
            }
        if(!empty($time_end) AND $time_end != 'Endtime'){
            $time_string .= ' AND time < $'.$i.'AT TIME ZONE \'UTC\'';
            $i++;
            $search_zeug[] = date('Y-m-d H:i:s',strtotime($time_end));
            }

        // sqlite workaround-block
        if($backend == "sqlite"){
                $method = 'LIKE';
                $input = strtolower($input); // sqlite workaround: also search caseinsensitive
            $i=1;
            $input_string = '';
            foreach($input_array AS $sonstwas){
                $input_string  .= 'AND lower(message) '.$method.' "%'.$sonstwas.'%"';
                $search_zeug[] = '%'.$sonstwas.'%';
                $i++;
            }
            $time_string = $time_start . '||' . $time_end;  //zusammenwurschten
            }



            // search with backend
            $outputary = $this->search_backend($input_string,$time_string,$search_zeug,$number,$types);
            
            $output = $outputary[0];

            if($outputary[1] == 0){
                $output .=  '<center>No results found for "'.$input.'" - please try another searchstring.</center>';}

    $Endzeit = $this->getmicrotime();   //zeit berechnen
    $output .= '<br><br><div style="font-size:6pt;text-align:center;">'.$outputary[1].' results in ' . number_format($Endzeit-$Anfangszeit, 4, ",", ".") . ' seconds.</div>';

  return $output;
  } 
}

?>