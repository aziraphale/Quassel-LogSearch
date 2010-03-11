<?php
    if((isset($loggedin)) AND $loggedin == TRUE){
        ?>
<a href="logout.php"><?php echo _('logout')?></a>&nbsp;|&nbsp;<a href="index.php?force_standard=TRUE"><?php echo _('standard')?></a>&nbsp;|&nbsp;<a href="javascript:Request('scontent','about.php');"><?php echo _('about')?></a>
<?php
    }
?></center></div><br><br></body></html>