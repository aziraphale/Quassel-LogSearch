<?php
    if((isset($loggedin)) AND $loggedin == TRUE){
        ?>
<a href="logout.php"><?=_('logout')?></a>&nbsp;|&nbsp;<a href="index.php?force_standard=TRUE"><?=_('standard')?></a>&nbsp;|&nbsp;<a href="javascript:Request('scontent','about.php');"><?=_('about')?></a>
<?
    }
?></center></div><br><br></body></html>