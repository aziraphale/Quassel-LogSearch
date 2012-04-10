<?php
//
//      Quassel Backlog Search
//      developed 2009-2011 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
require_once('classes/gettext.class.php');
require_once('config.php');
if($livesearch != 'true')
  {
  $searcher = 'such';
  }else{
  $searcher = 'rellig';
  }
?><div style="width:100%; background: #6699cc;color:white;" >
<!-- menu -->
<span style="position:absolute; top:0px;right:0px;margin:10px;"><a href="logout.php"><?php echo _('logout'); ?></a>&nbsp;|&nbsp;<a href="javascript:Request('scontent','faq.php');"><?php echo _('faq'); ?></a>&nbsp;|&nbsp;<a href="javascript:Request('scontent','about.php');"><?php echo _('about'); ?></a></span>
<span id="load" style="display:none;position:absolute; top:5px;left:5px;z-index:99"><img src="style/loading.gif" style="border:1px solid black;"></span>
<img style="position:relative;float:left;margin:10px;margin-bottom:-20px; margin-right:0px;" alt="" src="style/quassel.png">
<div style="overflow:none; float:left;">

<form onsubmit="javascript:<?php echo $searcher; ?>(); return false" action="#">
<div style="padding: 20px; float:left;">
<span class="link" style="font-size: 20pt;color:white;"><?php echo _('Quassel Backlog Search'); ?></span><br><br>
<input value="" id="input" size="60" type="search" x-webkit-speech />&nbsp;<input type="submit" value="<?php echo _('Start searching!'); ?>" class="button">
<br><label for="buffername"><img src="style/channel.png" style="z-index:1;position:relative;margin:2px;float:left;" alt="Buffer" title="<?php echo _('Buffer to search'); ?>"></label>
<script type="text/javascript">
    <!--
        javascript:document.getElementById('input').focus();
    //-->
</script>
<select  title="<?php echo _('Chat to search'); ?>" style="float:left;position:relative;z-index:99;" id="buffer" name="buffername" size="1">
<?php

    require("config.php");
    require_once('classes/'.$backend.'.class.php');
    $backendclass=new backend();
    $array = $backendclass->bufferids($userid);
$i = NULL;
foreach($array as $string){
$array2 = explode('||',$string);
if(!empty($array2[0])){
   if($i != $array2[2]){
    echo '<optgroup label="'.$backendclass->networkname($array2[2]).'">';
    }
   echo '<option value="'.$array2[1].'">'.$array2[0].'</option>';
   $i = $array2[2];    
   }}

?>
</select><label for="number"><img src="style/lines.png" title="<?php echo _('Number of results'); ?>" alt="Lines" style="margin:2px; margin-left:20px;float:left;"></label><input name="number" title="<?php echo _('Number of results'); ?>" value="<?php echo $defaultnr?>" id="number" size="5" step="5" min="5" oninvalid="roundthis(this);" type="number" max="100" style="text-align:right" autocomplete="off">
<span id="asearch" onclick="show_a_search();" style="font-size:7pt;margin-left:20px;" class="link" title="<?php echo _('Show advanced search'); ?>">[ <?php echo _('Advanced search'); ?> ]</span>
<br> </div>
<div id="advanced" style="margin: 5px; padding: 7px; padding-left: 15px; float:left;display:none; font-size:7pt; border-left:1px solid white;">
    <span onclick="hide_a_search();" style="margin-left:-10px;" class="link" title="<?php echo _('Close'); ?>">[ x ]</span><br><br>
    <?php echo _('Search in a timeperiod:'); ?><br>
    <input type="date" onchange="validtime_start();" title="<?php echo _('Excepts any english timeformat'); ?>" name="time_start" id="time_start" size="20;" value="Starttime" onBlur="if(this.value=='') this.value='Starttime';" onFocus="if(this.value=='Starttime') this.value='';">
    <input type="date" onchange="validtime_end();" title="<?php echo _('Excepts any english timeformat'); ?>" name="time_end" id="time_end" size="20;" value="Endtime" onBlur="if(this.value=='') this.value='Endtime';" onFocus="if(this.value=='Endtime') this.value='';">
    <span id="starttime" style="display:none;"></span><span id="endtime" style="display:none;"></span>
<br>
<input style="display:none;" name="sorting" type="checkbox" id="sorting" title="<?php echo _('Switch between DESC and ASC-Sorting; Default: DESC'); ?>">

<div style="clear:both;"><label for="regexid"><?php echo _('Regex:'); ?></label><input type="checkbox" name="regexid" id="regexid" title="<?php echo _('search with regular expression'); ?>" style="margin-right:15px;">

<label for="multipleide"><?php echo _('Switch multiple'); ?>:</label><input type="checkbox" onChange="multiplejs();" name="multipleide" id="multipleide" title="<?php echo _('Switch between multi-search and single-search; Multisearch allows to search more than one Chat at once.'); ?>" style="margin-right:15px;">

<div style="display:inline;<?php if($backend == "sqlite"){ echo ' display:none;'; } ?>"><label for="types"><?php echo _('Only messages:'); ?></label><input type="checkbox" name="types" id="types" title="<?php echo _('search only messages - if _not_ checked joins,quits,... will be shown and searched'); ?>" checked="checked"></div></div>

</div></form></div>
</div><center>
<div style="clear:both;width:100%;"><div id="scontent" style="overflow:auto;padding: 20px; text-align:left; vertical-align:top;"><font style="font-size:8pt;"><center><?php echo _('Waiting for search ...'); ?></center></font></div></div>
