<?php

    require("config.php");
    require_once('classes/'.$backend.'.class.php');
    $backend=new backend();

// create context

    $bufferid = $_REQUEST['bufferid'];
    $messageid = $_REQUEST['messageid'];
?>
<div style="margin:5px;"><span title="hide context" onclick="document.getElementById('m<?=$messageid?>').style.display = 'none'; document.getElementById('d<?=$messageid?>').style.display = 'block';">#</span>
<?php


echo $backend ->moreinfo($bufferid,$messageid);

?></div>