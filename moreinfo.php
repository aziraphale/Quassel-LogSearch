<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

    require_once('debuger.php');
    require("config.php");
    require_once('classes/parser.class.php');
    require_once('classes/'.$backend.'.class.php');
    $backend=new backend();

// create context

    $bufferid = $_REQUEST['bufferid'];
    $messageid = $_REQUEST['messageid'];
    $types = $_REQUEST['types'];
?>
<div style="margin:5px;"><a title="hide context" href="javascript:close_more(<?=$messageid?>)">#</a> <?php
echo $backend ->buffername($bufferid);?><br><a href="javascript:moreup('<?=$messageid?>','<?=$bufferid?>','up','<?=$types?>')">&and; more</a><div id="wantmore<?=$messageid?>"><?

echo $backend ->moreinfo($bufferid,$messageid,$types);

?></div><a href="javascript:moredown('<?=$messageid?>','<?=$bufferid?>','down','<?=$types?>')">&or; more</a><div id="wantmore<?=$messageid?>"></div>