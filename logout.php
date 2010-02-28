<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

require_once('debuger.php');
session_start();
session_destroy();
setcookie('login', 0, time()-60);
header('Location: ./index.php');
?>