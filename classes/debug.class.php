<?php
//debug class - optional

class debughandler {

    function backtrace(){
                $array = error_get_last();
                echo ':: <b>BT</b> ::<br><div style="margin-left:20px;margin-top:-0px">';
                     exception_error_handler($array['type'], $array['message'], $array['file'], $array['line']);
                echo '<pre style="margin-left:20px;margin-top:-0px">';
                     debug_print_backtrace();
                echo '</pre></div>';
        }
        
    function timeerror($string){
                echo '::<b>DEBUG</b>:: Invalid timeformat given in <i>'.$string.'</i> - will be ignored!<br>';
        }
        
    function debug($type=0,$string=0) {
        // nimmt fehler auf, leitet richtig weiter und gibt aus
        switch($type) {
            case 0: // backtrace fehler
                $this->backtrace();
                break;
            case 1: // timeerror
                $this->timeerror($string);
                break;
            default:
            die('Fatal Error - Unknown Errortype!');
            } 

        return;
        }

    }

class no_debughandler {

    function backtrace(){
        }
    
    function timeerror($string){
        }

    function debug($type=0,$string=0){
        }

    }

?>
