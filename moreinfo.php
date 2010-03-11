<?php
//
//      Quassel Backlog Search
//      developed 2009-2010 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
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
<div style="margin:5px;"><span style="color:#4a4a4a !important; background: white !important;"><a title="<?php echo _('hide context')?>" href="javascript:close_more(<?php echo $messageid?>)">#</a> <?php
echo $backend ->buffername($bufferid);?></span><br><a href="javascript:moreup('<?php echo $messageid?>','<?php echo $bufferid?>','<?php echo $up?>','<?php echo $types?>','<?php echo $sorting?>')" style="color:#4a4a4a !important; background: white !important;"><img style="float:left;" src="style/format-text-direction-rtl.png"> <?php echo_('more')?></a><div style="clear:left;" id="wantmore<?php echo $messageid?>"><?php

echo $backend ->moreinfo($bufferid,$messageid,$types,$sorting);

?></div><a href="javascript:moredown('<?php echo $messageid?>','<?php echo $bufferid?>','<?php echo $down?>','<?php echo $types?>','<?php echo $sorting?>')" style="color:#4a4a4a !important; background: white !important;"><img style="float:left;" src="style/format-text-direction-ltr.png"> <?php echo _('more')?></a><div style="clear:left;" id="wantmore<?php echo $messageid?>"></div>