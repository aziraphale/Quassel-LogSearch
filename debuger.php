<?php

// set errors dead
ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
ini_set('html_errors', 0);

// include classes
require('config.php');
require('classes/debug.class.php');

// define error-handler
function exception_error_handler($errno, $errstr, $errfile, $errline) {
        $fp = fopen ('debug-'.date('d-m-Y-H').'.log','a+');
        fwrite ($fp,$errstr.' - '.array_pop(explode('/',$errfile)).':'.$errline.' ('.$errno.')'."\n");
        fclose ($fp);
        $array = explode('/',$errfile);
    echo '::<b>DEBUG</b>:: <i>'.$errstr.'</i> - '.array_pop($array).':'.$errline.' ('.$errno.')<br>';
    return;
}

// switch debug:normal
if($debug == 'debug'){
    set_error_handler("exception_error_handler", E_ALL);    //set handler
    $debuger = new debughandler();
    }else{  //set no handler => no errors.
        $debuger = new no_debughandler();
        }

function debug($type=0,$string=0){  //pipe to debug()
    $GLOBALS['debuger']->debug($type,$string);
    return;
    }
    
?>