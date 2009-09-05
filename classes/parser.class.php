<?php
class parser{
    
    function format($line){
        
        return $line;
        }
    
    
    function parse($search_ary,$user,$types,$more=0){
           $output = "\n".'<div class="wrap" id="d'. $search_ary[0] .'"><span onclick="moreinfo(\''. $search_ary[0] .'\',\''. $search_ary["bufferid"] .'\',\''. $types .'\');" title="show context">#&nbsp;</span><font class="date" style="color:c3c3c3;">['.date("H:i:s d.m.y",$addtime +strtotime($search_ary["time"])).']</font>&nbsp;';
           switch(intval($search_ary["type"])){
            //all
            case 1:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;<b>'.$user[0].'</b>&gt;</font>&nbsp;' . htmlspecialchars($search_ary["message"]);
                break;
            // /me
            case 4:
                $output1 .= '<font style="color:#0000ff;">&nbsp;-*-</font>&nbsp;<b>'.$user[0].'</b> ' . htmlspecialchars($search_ary["message"]);
                break;
           //nickchange
           case 8:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;-&gt;</font>&nbsp;<b>'.$user[0].'</b> is known as <b>' . htmlspecialchars($search_ary["message"]).'</b>';
                break;
            //join
            case 32:
                $output1 .= '<font style="color:#0000ff;">&nbsp;--&gt;</font>&nbsp;<b>'.$user[0].'</b> has joined ' . htmlspecialchars($search_ary["message"]);
                break;
           //quit
            case 128:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;--</font>&nbsp;<b>'.$user[0].'</b> has quit (' . htmlspecialchars($search_ary["message"]).')';
                break;
            case 64:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;--</font>&nbsp;<b>'.$user[0].'</b> has quit (' . htmlspecialchars($search_ary["message"]).')';
                break;
           //kick
            case 256:
                $output1 .= '<font style="color:#0000ff;">&nbsp;&lt;-*</font>&nbsp;<b>'.$user[0].'</b> has kicked <b>'.substr($search_ary["message"],0,strpos($search_ary["message"]," ")).'</b> (' . str_replace('"','',substr($search_ary["message"],strpos(htmlspecialchars($search_ary["message"]),' ')+1)).')';
                break;
            //topic  
            case 1024:
                if(trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Topic' OR trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Channel'){
                    $error ='ERROR!';
                    break;
                    }
                $output1 .= ' * <b>' .substr($search_ary["message"],0,strpos($search_ary["message"]," ")) . '</b> has changed the topic to: ' . str_replace('"','',substr($search_ary["message"],strpos($search_ary["message"],'"')));
                break;
            case 16384:
                if(trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Topic' OR trim(substr($search_ary["message"],0,strpos($search_ary["message"]," "))) == 'Channel'){
                    $error ='ERROR!';
                    break;
                    }
                $output1 .= ' * <b>' .substr($search_ary["message"],0,strpos($search_ary["message"]," ")) . '</b> has changed the topic to: ' . str_replace('"','',substr($search_ary["message"],strpos($search_ary["message"],'"')));
                break;
             default:
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