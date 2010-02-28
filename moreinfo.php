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
    $sorting = $_REQUEST['sorting'];
    if($sorting == 1){
        $down = 'up';
        $up = 'down';
        }else{
            $down = 'down';
            $up = 'up';
            }
?>
<div style="margin:5px;"><a title="<?=_('hide context')?>" href="javascript:close_more(<?=$messageid?>)">#</a> <?php
echo $backend ->buffername($bufferid);?><br><a href="javascript:moreup('<?=$messageid?>','<?=$bufferid?>','<?=$up?>','<?=$types?>','<?=$sorting?>')" style="color:#4a4a4a !important; background: white !important;"><img style="float:left;" src="style/format-text-direction-rtl.png"> <?=_('more')?></a><div style="clear:left;" id="wantmore<?=$messageid?>"><?

echo $backend ->moreinfo($bufferid,$messageid,$types,$sorting);

?></div><a href="javascript:moredown('<?=$messageid?>','<?=$bufferid?>','<?=$down?>','<?=$types?>','<?=$sorting?>')" style="color:#4a4a4a !important; background: white !important;"><img style="float:left;" src="style/format-text-direction-ltr.png"> <?=_('more')?></a><div style="clear:left;" id="wantmore<?=$messageid?>"></div>