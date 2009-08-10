<div style="width:100%; background: #6699cc;color:white;" >
<!-- menu -->
<a style="margin:10px;float:right;" href="javascript:Request('scontent','about.php');">about</a>
<img style="position:relative;float:left;margin:10px;margin-bottom:-20px; margin-right:60px;" alt="" src="style/quassel.png">
<div style="min-width:900px;overflow:none; float:left; ">

<form onsubmit="javascript:such(); return false" action="#">
<div style="padding: 20px; float:left; ">
<span style="font-size: 20pt;color:white;">Quassel Backlog Search</span><br><br>
<input value="" id="input" size="60" type="text"">&nbsp;<input type="submit" value="Start searching!" class="button">
<br><img src="style/channel.png" style="z-index:1;position:relative;margin:2px;float:left;" alt="Buffer" title="Buffer to search">
<script type="text/javascript">
    <!--
        javascript:document.getElementById('input').focus();
    //-->
</script>
<select  title="Buffer to search" style="float:left;" id="buffer" name="buffername" size="1">

<?php

    require("config.php");
    require_once('classes/'.$backend.'.class.php');
    $backendclass=new backend();
    $array = $backendclass->bufferids($userid);

foreach($array as $string){
$array2 = explode('||',$string);
if(!empty($array2[0])){
   if($i != $array2[2]){
    echo '<optgroup label="'.$backendclass->networkname($array2[2]).'">';
    }
   echo '<option'.$echob.' value="'.$array2[1].'">'.$array2[0].'</option>';
   $i= $array2[2];    
   }}


?>
</select><img src="style/lines.png" title="Number of results" alt="Lines" style="margin:2px; margin-left:20px;float:left;"><input title="Number of results" value="100" id="number" size="3" type="text" maxlength="4"  autocomplete="off">
<span id="asearch" onclick="show_a_search();" style="font-size:7pt;margin-left:20px;" title="Show advanced search">[ Advanced search ]</span>
<br> </div>
<div id="advanced" style="margin: 7px; padding: 7px; padding-left: 15px; float:left;display:none; font-size:7pt; border-left:1px solid white;">
    <span onclick="hide_a_search();" style="margin-left:-10px;" title="Close">[ Advanced search ]</span><br>
    Search in a timeperiod:<br>
    <input title="Excepts any english timeformat" name="time_start" id="time_start" size="20;" value="Starttime" onBlur="if(this.value=='') this.value='Starttime';" onFocus="if(this.value=='Starttime') this.value='';">
    <input title="Excepts any english timeformat" name="time_end" id="time_end" size="20;" value="Endtime" onBlur="if(this.value=='') this.value='Endtime';" onFocus="if(this.value=='Endtime') this.value='';">
<br>

<div<? if($backend == "sqlite"){ echo ' style="display:none;"'; } ?>>Regex:<input type="checkbox" name="regexid" id="regexid" title="search with regular expression"></div>

<br>
</div></form></div>
</div><center>
<div style="clear:both;width:100%;"><div id="scontent" style="overflow:auto;padding: 20px; text-align:left; vertical-align:top;"><font style="font-size:8pt;"><center>Waiting for search ...</center></font></div></div>