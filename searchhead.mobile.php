<span id="load" style="display:none;position:absolute; top:54px;left:50%;margin:-35px;z-index:99"><img src="style/loading.gif" style="border:1px solid black;"></span>
<div style="width:100%; background: #6699cc;color:white;" ><center><form onsubmit="javascript:such(); return false" action="#">
<span style="font-size: 11pt;font-weight:bold;color:white;margin:3px;"><?php echo _('Quassel Backlog Search')?></span><br>
<input value="" id="input" size="40" type="text"">
<script type="text/javascript">
    <!--
        javascript:document.getElementById('input').focus();
    //-->
</script>
<br><select title="<?php echo _('Chat to search')?>" id="buffer" name="buffername" size="1">
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
</select>&nbsp;&nbsp;<input type="submit" value="<?php echo _('Search!'); ?>" class="button">
<input type="hidden" id="number" name="number" value="20">
<input type="hidden" id="time_start" name="time_start" value="">
<input type="hidden" id="time_end" name="time_end" value="">
<input type="checkbox" id="regexid" name="regexid" style="display:none;">
<input type="checkbox" id="types" name="types" style="display:none;" checked="checked">
<input style="display:none;" name="sorting" type="checkbox" id="sorting">
</form></center></div>
<div style="clear:both;width:100%;"><div id="scontent" style="overflow:auto;padding: 5px; text-align:left; vertical-align:top;"><font style="font-size:8pt;"><center><?php echo _('Waiting for search ...')?></center></font></div></div><center>