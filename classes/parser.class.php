<?php
//
//      Quassel Backlog Search - classes
//      developed 2009-2011 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
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
	$line = ' '.$line. "\x0F ";  // regexworkaround
	$lock = array('b'=>Null,'i'=>Null,'u'=>Null,'c'=>Null,'f'=>Null);
	$start = 1;
	$classes = Null;
	while($start != 0){
		$pos = Null;

		$pos['b'] = strpos($line,"\x02",$start);
		$pos['i'] = strpos($line,"\x1D",$start);
		$pos['u'] = strpos($line,"\x1F",$start);
		$pos['c'] = strpos($line,"\x03",$start);
		$pos['f'] = strpos($line,"\x0F",$start);
	$pos_2 = array_filter($pos);	

	if($pos_2){
	$start = min($pos_2);
//parse.
switch($start){
case $pos['b']:
	if($lock['b'] == 0){
$classes['b'] = 'fett';
$strclasses = '<span class="'.implode(' ',$classes).'">';
	$line = preg_replace('/\x02/', $strclasses, $line,1);
	$lock['b'] = 1;
	}else{
	unset($classes['b']);
	if($classes){
$strclasses = '</span><span class="'.implode(' ',$classes).'">';
	$line = preg_replace('/\x02/', $strclasses, $line,1);
	}else{
	$line = preg_replace('/\x02/', '</span>', $line,1);
	}
	$lock['b'] = 0;
	}
	break;
case $pos['i']:
	if($lock['i'] == 0){
$classes['i'] = 'it';
$strclasses = '<span class="'.implode(' ',$classes).'">';
	$line = preg_replace('/\x1D/', $strclasses, $line,1);
	$lock['i'] = 1;
	}else{
	unset($classes['i']);
	if($classes){
$strclasses = '</span><span class="'.implode(' ',$classes).'">';
	$line = preg_replace('/\x1D/', $strclasses, $line,1);
	}else{
	$line = preg_replace('/\x1D/', '</span>', $line,1);
	}
	$lock['i'] = 0;
	}
	break;
case $pos['u']:
	if($lock['u'] == 0){
$classes['u'] = 'un';
$strclasses = '<span class="'.implode(' ',$classes).'">';
	$line = preg_replace('/\x1F/', $strclasses, $line,1);
	$lock['u'] = 1;
	}else{
	unset($classes['u']);
	if($classes){
$strclasses = '</span><span class="'.implode(' ',$classes).'">';
	$line = preg_replace('/\x1F/', $strclasses, $line,1);
	}else{
	$line = preg_replace('/\x1F/', '</span>', $line,1);
	}
	$lock['u'] = 0;
	}
	break;
case $pos['c']:
	if($lock['c'] == 0){
$classes['c'] = "mirc\\1";
$strclasses = '<span class="'.implode(' ',$classes).'">';
	                    //farbe
                    $line = preg_replace('/(((?<=(\S|.)\x03)|\n)[0-9a-fA-F]{1,2}((\,)|\n)[0-9a-fA-F]{1,2}|((?<=(\S|.)\x03)|\n)[0-9a-fA-F]{1,2}+)/', $strclasses, $line,1,$n);
                        //bgcolor
                        $line = preg_replace('/((?<=(\S|.)mirc[0-9a-fA-F]{2})|\n)(?!([0-9a-fA-F]{2})),/', ' mircbg\\1', $line,1,$p);
                    $line = preg_replace('/\x03/', '', $line,1,$i); // aufräumen
	$lock['c'] = 1;
	}else{
	unset($classes['c']);
	if($classes){
$strclasses = '</span><span class="'.implode(' ',$classes).'">';
	$line = preg_replace('/\x03/', $strclasses, $line,1);
	}else{
	$line = preg_replace('/\x03/', '</span>', $line,1);
	}
	$lock['c'] = 0;
	}
	break;
case $pos['f']:
	$ref_f = 0;
	if($lock['b']==1){
	$ref_f = 1;
	$lock['b'] = 0;
	}
	if($lock['i']==1){
	$ref_f = 1;
	$lock['i'] = 0;
	}
	if($lock['u']==1){
	$ref_f = 1;
	$lock['u'] = 0;
	}
	if($lock['c']==1){
	$ref_f = 1;
	$lock['c'] = 0;
	}
	if($ref_f == 1){
	$ref_f = '</span>';
	}else{
	$ref_f = '';}
	$classes = Null;
	$line = preg_replace('/\x0F/', $ref_f, $line,1);

	break;
}
	$start++;
	}else{
	$start = 0;}			
	}
	return trim($line);
	}



    function mirc_alt($line){
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
                    $line = preg_replace('/(((?<=(\S|.)\x03)|\n)[0-9a-fA-F]{1,2}((\,)|\n)[0-9a-fA-F]{1,2}|((?<=(\S|.)\x03)|\n)[0-9a-fA-F]{1,2}+)/', $refa, $line,1,$n);
                        //bgcolor
                        $line = preg_replace('/((?<=(\S|.)mirc[0-9a-fA-F]{1,2})|\n)(?!([0-9a-fA-F]{1,2})),/', ' mircbg\\1', $line,1,$p);
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
           $addtime = 36*$timezone;

           $output = "\n".'<div style="display:table;width:100%;"><div class="wrap" id="d'. $search_ary[0] .'"><div class="date2 cell"><a href="javascript:moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\',\''. $types .'\',\''. $sorting .'\');" title="'._('show context').'" style="font-size:7pt;">[&#8230;]&nbsp;</a><font class="date" style="color:c3c3c3;">['.date($dateformat,$addtime +@strtotime($search_ary["time"])).']</font>&nbsp;</div>'; // hautpsuche
           // alle unterstützten types: 1,4,8,32,128,64,256,1024,16384
           switch(intval($search_ary["type"])){
            //all
            case 1:
                $output1 .= '<div class="nick">&nbsp;&lt;'.$usern[0].'&gt;&nbsp;</div><div class="msg cell">' . $this->format($search_ary["message"]).'</div>';
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
                $output = '<div class="wrap"><div class="date2 cell"'.$hl.'><font class="date" style="color:inherit;">['.date($dateformat,$addtime +strtotime($search_ary["time"])).']</font>&nbsp;</div>'.$output1.'</div>';
                if($this->mobile == TRUE){ // message in new line in more if mobile
                    if($hl == 1){
                        $hl = ' style="color:black;"';
                        }else{
                            $hl = ' style="color:#c6c6c6;"';}
                    $output = '<font class="date"'.$hl.'>['.date($dateformat,$addtime +strtotime($search_ary["time"])).']</font>'.$output1;
                    }
                }elseif($this->mobile == TRUE){ //mobile braucht kein datum und more usw ...
                    $output = "\n".'<div style="display:table;width:100%;"><div style="text-align:left;float:left;" class="wrap" id="d'. $search_ary[0] .'"><div class="cell"><a href="javascript:moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\',\''. $types .'\',\''. $sorting .'\');" title="'._('show context').'" style="font-size:7pt;">[&#8230;]&nbsp;</a></div>'.$output1.'</div></div><div style="display:table;width:100%;"><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;">'._('Loading...').'</div></div>';
                    }else{ //hauptsuche
                        $output = $output . $output1.'</div></div><div style="display:table;width:100%;"><div class="wrap" id="m'. $search_ary[0] .'" style="display: none;color:black;">'._('Loading…').'</div></div>';  //hauptsuche ende
                        }       
        }

        return $output;
        }    


    }

?>
