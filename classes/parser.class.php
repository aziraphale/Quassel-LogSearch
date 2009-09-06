<?php
class parser{
    
    function make_link($text){
        //klickbare links
        $ret = ' ' . $text;
        $ret = preg_replace("#(^|[\n ])([\w]+?://.*?[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" class=\"links\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:/[^ \"\t\n\r<]*)?)#is", "\\1<a class=\"links\" href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = substr($ret, 1);
        return($ret);
        }
    
    function mirc($line){
        // mirc-formatierung
        $line = ' '.$line.' ';
        $i = 1;
        $m = 1;
        $l = 1;
        $j = 1;
        $n = 1;
        $lock = 0;
        while($i!=0 OR $m !=0){
            $lock++;
            if($lock == 100){
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
                        if($j%2 == 0 AND $n%2 == 0){
                            $line = preg_replace('//', '</b></font>', $line,1,$l);
                            if($l == 1){
                                $j++;
                                $n++;
                                continue;
                                }
                            }elseif($j%2 == 0){
                                $line = preg_replace('//', '</b>', $line,1,$l);
                                if($l == 1){
                                    $j++;
                                    continue;
                                    }
                                }elseif($n%2 == 0){
                                    $line = preg_replace('//', '</font>', $line,1,$l);
                                    if($l == 1){
                                        $n++;
                                        continue;
        }}}}
        if($j%2 == 0){
            $line = $line.'</b>';
            }
        if($n%2 == 0){
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
    
    
    function parse($search_ary,$user,$types,$more=0){
           $output = "\n".'<div class="wrap" id="d'. $search_ary[0] .'"><span onclick="moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\',\''. $types .'\');" title="show context">#&nbsp;</span><font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']</font>&nbsp;';
           //alle unterstützten types
           switch(intval($search_ary["type"])){
            //all
            case 1:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;'.$user[0].'&gt;</font>&nbsp;' . $this->format($search_ary["message"]);
                break;
            // /me
            case 4:
                $output1 .= '<font style="color:#0000ff;">&nbsp;-*-</font>&nbsp;<b>'.$user[0].'</b> ' . $this->format($search_ary["message"]);
                break;
           //nickchange
           case 8:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;-&gt;</font>&nbsp;<b>'.$user[0].'</b> is known as <b>' . $this->format($search_ary["message"]).'</b>';
                break;
            //join
            case 32:
                $output1 .= '<font style="color:#0000ff;">&nbsp;--&gt;</font>&nbsp;<b>'.$user[0].'</b> has joined ' . $this->format($search_ary["message"]);
                break;
           //quit
            case 128:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;--</font>&nbsp;<b>'.$user[0].'</b> has quit (' . $this->format($search_ary["message"]).')';
                break;
            case 64:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;--</font>&nbsp;<b>'.$user[0].'</b> has quit (' . $this->format($search_ary["message"]).')';
                break;
           //kick
            case 256:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;-*</font>&nbsp;<b>'.$user[0].'</b> has kicked <b>'.substr($search_ary["message"],0,strpos($search_ary["message"]," ")).'</b> (' . str_replace('"','',substr($search_ary["message"],strpos($this->format($search_ary["message"]),' ')+1)).')';
                break;
            //topic  
            case 1024:
                if(trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Topic' OR trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Channel'){
                    $error ='ERROR!';
                    break;
                    }
                $output1 .= ' * <b>' .substr($search_ary["message"],0,strpos($search_ary["message"]," ")) . '</b> has changed the topic to: ' . $this->format(str_replace('"','',substr($search_ary["message"],strpos($search_ary["message"],'"'))));
                break;
            case 16384:
                if(trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Topic' OR trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Channel'){
                    $error ='ERROR!';
                    break;
                    }
                $output1 .= ' * <b>' .substr($search_ary["message"],0,strpos($search_ary["message"]," ")) . '</b> has changed the topic to: ' . $this->format(str_replace('"','',substr($search_ary["message"],strpos($search_ary["message"],'"'))));
                break;
             default:
             // böse, das kann theoretisch garnicht passieren ...
                $error ='ERROR!';
            }        
        
        if(!empty($error)){
            $output = '';
            }else{
            if($more == 0){
                $output = $output . $output1.'</div><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;">Loading...</div>';
                }else{
                    $output = $output1;
                    }        
        }
        
        return $output;
        }    


    }
    
?>