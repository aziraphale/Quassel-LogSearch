<?php
// search.class
    require("config.php");
    require_once($backend.'.class.php');

class searchengine extends backend
{

function getmicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

function search($bufferid, $input,$number,$time_start,$time_end,$regex=0){
    require("config.php");
     $Anfangszeit = $this->getmicrotime();

        
        //prepare vars
        $search_zeug[] = $bufferid;
        
        // regex oder ilike?
        if($regex == 'true'){
            $method = '~*';
            }else{
                $method = 'ILIKE';

                }
                
             // sqlite workaround
             if($backend == "sqlite"){
                        $method = 'LIKE';
                        }
        
        $input_array = explode(" ",$input);
        $i=2;
        foreach($input_array AS $sonstwas){
            $input_string  .= 'AND lower(message) '.$method.' $'. $i;
            $search_zeug[] = '%'.$sonstwas.'%';
            $i++;
        }
        


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
            
        // sqlite workaround
        if($backend == "sqlite"){
        $i=2;
        $input_string = '';
        foreach($input_array AS $sonstwas){
            $input_string  .= 'AND lower(message) '.$method.' "%'.$sonstwas.'%"';
            $search_zeug[] = '%'.$sonstwas.'%';
            $i++;
        }
        $time_string = $time_start . '||' . $time_end;
            }
            
                       

            // search with backend
            $outputary = $this->search_backend($input_string,$time_string,$search_zeug,$number);
            
            $output = $outputary[0];

            if($i == 0){
                $output .=  '<center>No results found for "'.$input.'" ...</center>';}

    $Endzeit = $this->getmicrotime();
    $output .= '<br><br><div style="font-size:6pt;text-align:center;">'.$outputary[1].' results in ' . number_format($Endzeit-$Anfangszeit, 4, ",", ".") . ' seconds.</div>';

  return $output;
  } 
}

?>