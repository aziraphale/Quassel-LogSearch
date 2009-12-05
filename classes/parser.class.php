<?php
//
//      Quassel Backlog Search - classes
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

if (session_id() == ""){    //session, immer wichtig, va böse wenn man sie vergisst ...
    @session_start();
}
    if((isset($_SESSION['mobile'])) AND $_SESSION['mobile'] == TRUE){ // externe vars für konstruktor
        $mobile = TRUE;
        }

// message parsing-class
class parser{

    var $mobile;

    function __construct(){ //konstruktor prüft, ob mobile oder nicht
        if(isset($GLOBALS['mobile'])){
        $this->mobile=$GLOBALS['mobile'];
        }}

    function make_link($text){
        //klickbare links
        $ret = ' ' . $text;
        $ret = preg_replace("#(.|^|[\n ])([\w]+?://.*?[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" class=\"links\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:/[^ \"\t\n\r<]*)?)#is", "\\1<a class=\"links\" href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = substr($ret, 1);
        return($ret);
        }
    
    function mirc($line){
        // mirc-formatierung - evil!
        $line = ' '.$line.' ';  // regexworkaround
        //vars,vars,vars ...
        $i = 1;
        $m = 1;
        $l = 1;
        $j = 1;
        $n = 1;
        $lock = 0;
        while($i!=0 OR $m !=0){
            $lock++;
            if($lock == 100){   // endlosschleife verhindern, sollte nicht passieren, aber sicher ist sicher
                //DEBUG echo 'ENDLOS!!!';
                break;
                }
            if($j%2 == 0){
                $refe = '</b>';
                }else{
                    $refe = '<b>';
                    }
            if($n%2 == 0){
               //neue farbe anfangen
               $refa = "</font><font class=\"mirc\\1\">";
                }else{
                    $refa = "<font class=\"mirc\\1\">";
                    }
            $posfe = strpos($line,'',0);
            $posfa = strpos($line,'',0);
            $posen = strpos($line,'',0);
                // weitermachen, selbst wenn eins 0 ist.
                if($posfe === FALSE){
                    $i = 0;
                    $posfe = 1001;}
                if($posfa === FALSE){
                    $m = 0;
                    $posfa = 1001;}
                if($posen === FALSE){
                    $l = 0;
                    $posen = 1000;}
              //DEBUG echo $posfe.'!'.$posfa.'!'.$posen.'!'.$i.'!'.$m.'<br>';
            if($posfe < $posfa AND $posfe < $posen){
                //fett
                $line = preg_replace('//', $refe, $line,1,$i);
                    $j++;
                }elseif($posfa < $posfe AND $posfa < $posen){
                    //farbe
                    $line = preg_replace('/(((?<=(\S|.))|\n)[0-9a-fA-F]{2}((\,)|\n)[0-9a-fA-F]{2}|((?<=(\S|.))|\n)[0-9a-fA-F]{2}+)/', $refa, $line,1,$n);
                        //bgcolor
                        $line = preg_replace('/((?<=(\S|.)mirc[0-9a-fA-F]{2})|\n)(?!([0-9a-fA-F]{2})),/', ' mircbg\\1', $line,1,$p);
                    $line = preg_replace('//', '', $line,1,$i); // aufräumen
                        $n++;
                    }else{
                        if($j%2 == 0 AND $n%2 == 0){    // beide schließen
                            $line = preg_replace('//', '</b></font>', $line,1,$l);
                            if($l == 1){
                                $j++;
                                $n++;
                                continue;
                                }
                            }elseif($j%2 == 0){ //nur fett
                                $line = preg_replace('//', '</b>', $line,1,$l);
                                if($l == 1){
                                    $j++;
                                    continue;
                                    }
                                }elseif($n%2 == 0){ //nur farbe
                                    $line = preg_replace('//', '</font>', $line,1,$l);
                                    if($l == 1){
                                        $n++;
                                        continue;
        }}}}
        if($j%2 == 0){  // noch was offen?
            $line = $line.'</b>';
            }
        if($n%2 == 0){  //noch was offen?
            $line = $line.'</font>';
            }

        return trim($line);
        }

      
    function format($line){
        //formatierungszusammefassung
        $line = htmlspecialchars($line);
        $line = $this->make_link($line);
        $line = $this->mirc($line);
        return $line;
        }
    
    
    function parse($search_ary,$usern,$types,$more=0,$hl=0,$sorting=0){
        $output1 = '';
        //timezone support
        require('config.php');
             // summer || winter ?
             if(date('I')){
                $addtime = 36*($timezone+100);
                }else{
                    $addtime = 36*$timezone;
                    }

           $output = "\n".'<div style="display:table;width:100%;"><div class="wrap" id="d'. $search_ary[0] .'"><div class="cell"><a href="javascript:moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\',\''. $types .'\',\''. $sorting .'\');" title="'._('show context').'">#&nbsp;</a><font class="date" style="color:c3c3c3;">['.date($dateformat,$addtime +strtotime($search_ary["time"])).']</font>&nbsp;</div>'; // hautpsuche
           // alle unterstützten types: 1,4,8,32,128,64,256,1024,16384
           switch(intval($search_ary["type"])){
            //all
            case 1:
                $output1 .= '<div class="nick">&nbsp;&lt;'.$usern[0].'&gt;&nbsp;</div><div class="msg cell"">' . $this->format($search_ary["message"]).'</div>';
                break;
            // /me
            case 4:
                $output1 .= '<div class="nick">&nbsp;-*-&nbsp;</div><div class="msg cell"><b>'.$usern[0].'</b> ' . $this->format($search_ary["message"]).'</div>';
                break;
           //nickchange &lt;-&gt;
           case 8:
                $output1 .= '<div class="nick">&lt;-&gt;&nbsp;</div><div class="msg cell"><b>'.$usern[0].'</b> '._('is known as').' <b>' . $this->format($search_ary["message"]).'</b></div>';
                break;
            //join
            case 32:
                $output1 .= '<div class="nick">--&gt;&nbsp;</div><div class="msg cell"><b>'.$usern[0].'</b> '._('has joined').' ' . $this->format($search_ary["message"]).'</div>';
                break;
           //quit
            case 128:
                $output1 .= '<div class="nick">&lt;--&nbsp;</div><div class="msg cell"><b>'.$usern[0].'</b> '._('has quit').' (' . $this->format($search_ary["message"]).')</div>';
                break;
            case 64:
                $output1 .= '<div class="nick">&lt;--&nbsp;</div><div class="msg cell"><b>'.$usern[0].'</b> '._('has quit').' (' . $this->format($search_ary["message"]).')</div>';
                break;
           //kick
            case 256:
                $output1 .= '<div class="nick">&lt;-*&nbsp;</div><div class="msg cell"><b>'.$usern[0].'</b> '._('has kicked').' <b>'.substr($search_ary["message"],0,strpos($search_ary["message"]," ")).'</b>'._(' ').'(' . str_replace('"','',substr($search_ary["message"],strpos($this->format($search_ary["message"]),' ')+1)).')</div>';
                break;
            //topic  
            case 1024:
                if(trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Topic' OR trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Channel'){
                    $error ='ERROR!';
                    break;
                    }
                $output1 .= '<div class="nick">&nbsp;*&nbsp;</div><div class="msg cell"><b>' .substr($search_ary["message"],0,strpos($search_ary["message"]," ")) . '</b> '._('has changed the topic to:').' ' . $this->format(str_replace('"','',substr($search_ary["message"],strpos($search_ary["message"],'"')))).'</div>';
                break;
            case 16384:
                if(trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Topic' OR trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Channel'){
                    $error ='ERROR!';
                    break;
                    }
                $output1 .= '<div class="nick">&nbsp;*&nbsp;</div><div class="msg cell"><b>' .substr($search_ary["message"],0,strpos($search_ary["message"]," ")) . '</b> '._('has changed the topic to:').' ' . $this->format(str_replace('"','',substr($search_ary["message"],strpos($search_ary["message"],'"')))).'</div>';
                break;
             default:
             // böse, das kann theoretisch garnicht passieren ...
                $error ='ERROR!';
            }        
        if(!empty($error)){ // wenn kein fehler vorkommt kanns weitergehen, ansonsten wir keine ausgabe gemacht.
            $output = '';
            }else{
            if($more == 1){ // is more*?
                if($hl == 1){
                    $hl = ' style="color:black;"';
                    }else{
                        $hl = ' style="color:c3c3c3;"';}
                $output = '<div class="wrap"><div class="cell"'.$hl.'><font class="date" style="color:inherit;">['.date($dateformat,$addtime +strtotime($search_ary["time"])).']</font>&nbsp;</div>'.$output1.'</div>';
                if($this->mobile == TRUE){ // message in new line in more if mobile
                    $output = '<font class="date" style="color:inherit;">['.date($dateformat,$addtime +strtotime($search_ary["time"])).']</font>'.$output1;
                    }
                }elseif($this->mobile == TRUE){ //mobile braucht kein datum und more usw ...
                    $output = '<div class="wrap" id="d'. $search_ary[0] .'"><a href="javascript:moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\',\''. $types .'\');" title="'._('show context').'">#&nbsp;</a>'.$output1.'</div><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;">'._('Loading...').'</div>';
                    }else{ //hauptsuche
                        $output = $output . $output1.'</div></div><div style="display:table;width:100%;"><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;">'._('Loading...').'</div></div>';  //hauptsuche ende
                        }       
        }

        return $output;
        }    


    }

?>