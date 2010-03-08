<?php
//
//      Quassel Backlog Search
//      developed 2009-2010 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//


// parse timezone
    $dateTimeZone= new DateTimeZone($timezone);
    $dateTime = new DateTime('now', $dateTimeZone);
    ini_set('date.timezone', $timezone);
    $timezone = $dateTimeZone->getOffset($dateTime)/36;

$defaultnr = 20;
 
?>