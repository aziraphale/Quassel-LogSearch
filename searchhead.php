<?php
require_once('classes/gettext.class.php');
?><div style="width:100%; background: #6699cc;color:white;" >
<!-- menu -->
<span style="position:absolute; top:0px;right:0px;margin:10px;"><a href="logout.php"><?=_('logout'); ?></a>&nbsp;|&nbsp;<a href="javascript:Request('scontent','faq.php');"><?=_('faq'); ?></a>&nbsp;|&nbsp;<a href="javascript:Request('scontent','about.php');"><?=_('about'); ?></a></span>
<span id="load" style="display:none;position:absolute; top:5px;left:5px;z-index:99"><img src="style/loading.gif" style="border:1px solid black;"></span>
<img style="position:relative;float:left;margin:10px;margin-bottom:-20px; margin-right:60px;" alt="" src="style/quassel.png">
<div style="overflow:none; float:left; ">

<form onsubmit="javascript:such(); return false" action="#">
<div style="padding: 20px; float:left; ">
<span style="font-size: 20pt;color:white;"><?=_('Quassel Backlog Search'); ?></span><br><br>
<input value="" id="input" size="60" type="text"">&nbsp;<input type="submit" value="<?=_('Start searching!'); ?>" class="button">
<br><label for="buffername"><img src="style/channel.png" style="z-index:1;position:relative;margin:2px;float:left;" alt="Buffer" title="<?=_('Buffer to search'); ?>"></label>
<script type="text/javascript">
    <!--
        javascript:document.getElementById('input').focus();
    //-->
</script>
<select  title="<?=_('Chat to search'); ?>" style="float:left;position:relative;z-index:99;" id="buffer" name="buffername" size="1">
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
</select><label for="number"><img src="style/lines.png" title="<?=_('Number of results'); ?>" alt="Lines" style="margin:2px; margin-left:20px;float:left;"></label><input name="number" title="<?=_('Number of results'); ?>" value="50" id="number" size="3" type="text" maxlength="4" style="text-align:right" autocomplete="off">
<span id="asearch" onclick="show_a_search();" style="font-size:7pt;margin-left:20px;" title="<?=_('Show advanced search'); ?>">[ <?=_('Advanced search'); ?> ]</span>
<br> </div>
<div id="advanced" style="margin: 5px; padding: 7px; padding-left: 15px; float:left;display:none; font-size:7pt; border-left:1px solid white;">
    <span onclick="hide_a_search();" style="margin-left:-10px;" title="<?=_('Close'); ?>">[ <?=_('Advanced search'); ?> ]</span><br>
    <?=_('Search in a timeperiod:'); ?><br>
    <input title="<?=_('Excepts any english timeformat'); ?>" name="time_start" id="time_start" size="20;" value="Starttime" onBlur="if(this.value=='') this.value='Starttime';" onFocus="if(this.value=='Starttime') this.value='';">
    <input title="<?=_('Excepts any english timeformat'); ?>" name="time_end" id="time_end" size="20;" value="Endtime" onBlur="if(this.value=='') this.value='Endtime';" onFocus="if(this.value=='Endtime') this.value='';">
<br>
<!--label for="sorting">ASC sorting</label--><input style="display:none;" name="sorting" type="checkbox" id="sorting" title="<?=_('Switch between DESC and ASC-Sorting; Default: DESC'); ?>">
<span onclick="multiple();" style="margin-left:0px;" title="<?=_('Switch between multi-search and single-search; Multisearch allows to search more than one Chat at once.'); ?>"><?=_('Switch multiple'); ?></span>
<div style="clear:both;<? if($backend == "sqlite"){ echo ' display:none;"'; } ?>><label for="regexid"><?=_('Regex:'); ?></label><input type="checkbox" name="regexid" id="regexid" title="<?=_('search with regular expression'); ?>" style="margin-right:15px;">
<label for="types"><?=_('Only messages:'); ?></label><input type="checkbox" name="types" id="types" title="<?=_('search only messages - if _not_ checked joins,quits,... will be shown and searched'); ?>" checked="checked"></div>

</div></form></div>
</div><center>
<div style="clear:both;width:100%;"><div id="scontent" style="overflow:auto;padding: 20px; text-align:left; vertical-align:top;"><font style="font-size:8pt;"><center><?=_('Waiting for search ...'); ?></center></font></div></div>