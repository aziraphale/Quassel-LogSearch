<?php
//
//      Quassel Backlog Search - classes
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

// main search.class
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

function search($bufferid, $input,$number,$time_start,$time_end,$regex=0,$types=1,$sorting=0){
    require("config.php");
     $Anfangszeit = $this->getmicrotime();
    if(empty($bufferid)){
            echo '<center>'._('No results - please select a Chat.').'</center>';
            $Endzeit = $this->getmicrotime();
            echo '<br><br><div style="font-size:6pt;text-align:center;">'._('No results in ') . number_format($Endzeit-$Anfangszeit, 4, ",", ".") . _(' seconds.').'</div>';
            die(1);
        }

        //prepare vars
        $search_zeug[] = $bufferid;

        $input_string = '';
        $ssary = NULL;
        //regex braucht kein externes or, wer regex nutz, soll auch das or so machen ;)
        if($regex != 'true'){
             $method = 'ILIKE';
            $input_array = explode(' ',$input);
            $i=1;
            foreach($input_array AS $sonstwas){
                if(preg_match_all("^((?<=(\S|.)ender=)|\n)[A-Za-z\_\|\^]+[A-Za-z0-9\_\|\^]*^",$sonstwas,$ssearch)){ //nicksuche?
                    $ssary[] = '%'.$ssearch[0][0].'%';
                    continue;
                    }
                $input_string  .= 'AND lower(message) '.$method.' $'. $i;
                $search_zeug[] = '%'.$sonstwas.'%';
                $i++;
            }
         }else{ // regex
            $input_string  .= 'AND message ~* $1';
            $search_zeug[] = $input;
            }

        // zeitspannensuche?
        if(strtotime($time_start) === false){   //valid?
            debug(1,$time_start);
            $time_start = NULL;
            }
        if(strtotime($time_end) === false){ //valid?
            debug(1,$time_end);
            $time_end = NULL;
            }
            $time_string = NULL;

        if(!empty($time_start)){
            $time_string .= ' AND time > $'.$i.'AT TIME ZONE \'UTC\'';
            $i++;
            $search_zeug[] = date('Y-m-d H:i:s',strtotime($time_start));
            }
        if(!empty($time_end)){
            $time_string .= ' AND time < $'.$i.'AT TIME ZONE \'UTC\'';
            $i++;
            $search_zeug[] = date('Y-m-d H:i:s',strtotime($time_end));
            }

       

        // sqlite workaround-block
        if($backend == "sqlite"){
            $time_string = '';
                $method = 'LIKE';
                $input = strtolower($input); // sqlite workaround: also search caseinsensitive
            $i=1;
            $input_string = '';
            $ssary = NULL;
            foreach($input_array AS $sonstwas){
                if(preg_match_all("^((?<=(\S|.)ender=)|\n)[A-Za-z\_\|\^]+[A-Za-z0-9\_\|\^]*^",$sonstwas,$ssearch)){ //nicksuche?
                    $ssary[] = '%'.$ssearch[0][0].'%';
                    continue;
                    }
                $input_string  .= 'AND lower(message) '.$method.' "%'.$sonstwas.'%"';
                $search_zeug[] = '%'.$sonstwas.'%';
                $i++;
            }
            $time_string = $time_start . '||' . $time_end;  //zusammenwurschten
            }

            echo '::DEBUG::'.$input.'<br><br>';

            // search with backend
            $outputary = $this->search_backend($input_string,$time_string,$search_zeug,$number,$types,$sorting,$ssary);
            
            $output = $outputary[0];

            if($outputary[1] == 0){
                $output .=  '<center>'._('No results found for "').$input._('" - please try another searchstring.').'</center>';}

    $Endzeit = $this->getmicrotime();   //zeit berechnen
    $output .= '<br><br><div style="font-size:6pt;text-align:center;">'.$outputary[1]._(' results in ') . number_format($Endzeit-$Anfangszeit, 4, ",", ".") . _(' seconds.').'</div>';

  return $output;
  } 
}

?>