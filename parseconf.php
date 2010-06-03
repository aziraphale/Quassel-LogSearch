<?php
//
//      Quassel Backlog Search
//      developed 2009-2010 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//


// hidden configs
$defaultnr = 20;
$config_version2 = '1.0';
$required_php_version = '5.1.0';
$recommended_php_version = '5.2.0';
 

    // check php-version
    if(version_compare(PHP_VERSION, $required_php_version) !== 1) {
         die(_('<b>Your <u>php-Version</u> is too old - please update at least to '.$required_php_version.'!</b>'));
    }

// parse timezone

if(isset($timezone)){
    // workaround to get required to 5.1.0
    if (version_compare(PHP_VERSION, $recommended_php_version) !== 1) {
        if(date('I')){
            $timezone = intval(date('O',strtotime($timezone)))+100; 
            }else{
                $timezone = intval(date('O',strtotime($timezone))); }  
        }else{
            $dateTimeZone= new DateTimeZone($timezone);
            $dateTime = new DateTime('now', $dateTimeZone);
            ini_set('date.timezone', $timezone);
            $timezone = $dateTimeZone->getOffset($dateTime)/36;
            }}





?>