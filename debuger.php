<?php
require('config.php');
require('classes/debug.class.php');
if($debug == 'debug'){
    $debuger = new debug();
    }else{
        $debuger = new no_debug();
        }
?>