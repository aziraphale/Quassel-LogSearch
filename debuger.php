<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

// set errors dead
ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
ini_set('html_errors', 0);

// include classes
require('config.php');
require('classes/debug.class.php');

// define error-handler
function exception_error_handler($errno, $errstr, $errfile, $errline) {
        //log in hourly changing logfiles
        $fp = fopen ('debug-'.date('d-m-Y-H').'.log','a+');
        fwrite ($fp,date('d-m-Y-H:i:s').' - '.$errstr.' - '.array_pop(explode('/',$errfile)).':'.$errline.' ('.$errno.')'."\n");
        fclose ($fp);
        $array = explode('/',$errfile);
    //print error but dont die (only if fatal)
    echo '::<b>DEBUG</b>:: <i>'.$errstr.'</i> - '.array_pop($array).':'.$errline.' ('.$errno.')<br>';
    return;
}

// switch debugfull:debug:normal
if($debug == 'debugfull'){
    set_error_handler("exception_error_handler", -1);    //set fullhandler
    $debuger = new debughandler();  //set for further debuging and backtracing debug-objekt
    }elseif($debug == 'debug'){
        set_error_handler("exception_error_handler", E_ALL & ~E_STRICT);    //set handler
        $debuger = new debughandler();  //set for further debuging and backtracing debug-objekt
        }else{  //set no handler => no errors.
            $debuger = new no_debughandler();   //set empty object to avoid debuging
            }

function debug($type=0,$string=0){  //pipe to debug()
    $GLOBALS['debuger']->debug($type,$string);
    return;
    }
    
?>