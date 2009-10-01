<?php

ini_set('display_startup_errors', 0);
ini_set('display_errors', 0);
ini_set('html_errors', 0);

require('config.php');
require('classes/debug.class.php');

function exception_error_handler($errno, $errstr, $errfile, $errline) {
        $fp = fopen ('debug-'.date('d-m-Y-H').'.log','a+');
        fwrite ($fp,$errstr.' - '.array_pop(explode('/',$errfile)).':'.$errline.' ('.$errno.')'."\n");
        fclose ($fp);
    echo '::<b>DEBUG</b>:: <i>'.$errstr.'</i> - '.array_pop(explode('/',$errfile)).':'.$errline.' ('.$errno.')<br>';
    return;
}

if($debug == 'debug'){
    set_error_handler("exception_error_handler", E_ALL);
    $debuger = new debughandler();
    }else{
        $debuger = new no_debughandler();
        }
?>