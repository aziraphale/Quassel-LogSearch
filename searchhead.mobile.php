<div style="width:100%; background: #6699cc;color:white;" ><center><form onsubmit="javascript:such(); return false" action="#">
<span style="font-size: 11pt;font-weight:bold;color:white;margin:3px;">Quassel Backlog Search</span><br>
<input value="" id="input" size="40" type="text"">
<script type="text/javascript">
    <!--
        javascript:document.getElementById('input').focus();
    //-->
</script>
<br><select title="Chat to search" id="buffer" name="buffername" size="1">
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
</select>&nbsp;&nbsp;<input type="submit" value="Search!" class="button">
<input type="hidden" id="number" name="number" value="100">
<input type="hidden" id="time_start" name="time_start" value="">
<input type="hidden" id="time_end" name="time_end" value="">
<input type="checkbox" id="regexid" name="regexid" style="display:none;">
<input type="checkbox" id="types" name="types" style="display:none;" checked="checked">
</form></center></div>
<div style="clear:both;width:100%;"><div id="scontent" style="overflow:auto;padding: 5px; text-align:left; vertical-align:top;"><font style="font-size:8pt;"><center>Waiting for search ...</center></font></div></div><center>