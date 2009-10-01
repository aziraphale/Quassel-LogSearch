<?php
    require_once('debuger.php');require_once('debuger.php');
    require("config.php");
    require_once('classes/parser.class.php');
    require_once('classes/'.$backend.'.class.php');
    $backend=new backend();

// create context

    $bufferid = $_REQUEST['bufferid'];
    $messageid = $_REQUEST['messageid'];
    $types = $_REQUEST['types'];
?>
<div style="margin:5px;"><span title="hide context" onclick="document.getElementById('m<?=$messageid?>').style.display = 'none'; document.getElementById('d<?=$messageid?>').style.display = 'block';">#</span> <?php
echo $backend ->buffername($bufferid);?><br><span onclick="moreup('<?=$messageid?>','<?=$bufferid?>','up','<?=$types?>')">&and; more</span><div id="wantmore<?=$messageid?>"><?

echo $backend ->moreinfo($bufferid,$messageid,$types);

?></div><span onclick="moredown('<?=$messageid?>','<?=$bufferid?>','down','<?=$types?>')">&or; more</span><div id="wantmore<?=$messageid?>"></div>