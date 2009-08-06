<div style="width:100%; background: #6699cc;color:white;" >
<!-- menu -->
<a style="margin:10px;float:right;" href="javascript:Request('scontent','about.php');">about</a>
<img style="float:left;margin:10px;margin-bottom:-20px; margin-right:60px;" alt="" src="style/quassel.png">

<div style="padding: 20px; float:left;">
<form onsubmit="javascript:such(); return false" action="#">
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
require_once("config.php");
 $dbconn = pg_connect ("dbname=$dbname user=$user password='$password' port=$port host=$host");
 
// get bufferids und buffernames for user 
   $result = pg_query($dbconn,"SELECT buffername,bufferid FROM buffer WHERE userid = $userid;");

while($search_ary = pg_fetch_array($result)) {
$array[] = $search_ary[0] .'||'. $search_ary[1];
}
natcasesort($array);

foreach($array as $string){
$array2 = explode('||',$string);
if(!empty($array2[0])){
   echo '<option'.$echob.' value="'.$array2[1].'">'.$array2[0].'</option>';    
   }}

pg_close($dbconn); 
?>
</select><img src="style/lines.png" title="Number of results" alt="Lines" style="margin:2px; margin-left:20px;float:left;"><input title="Number of results" value="100" id="number" size="3" type="text" maxlength="4"  autocomplete="off">
<span onclick="document.getElementById('advanced').style.display='block';" style="font-size:7pt;margin-left:20px;">[ Advanced search ]</span>
<br> </div>
<div id="advanced" style="margin: 7px; padding: 7px; float:left;display:none; font-size:7pt; border-left:1px solid white">
    <span onclick="document.getElementById('advanced').style.display='none';" style="margin-left:-3px;">[ Advanced search ]</span><br>
    Search in a period:<br>
    <input name="time_start" size="20;" value="Starttime" onBlur="if(this.value=='') this.value='Starttime';" onFocus="if(this.value=='Starttime') this.value='';">
    <input name="time_ende" size="20;" value="Endtime" onBlur="if(this.value=='') this.value='Endtime';" onFocus="if(this.value=='Endtime') this.value='';">
<br><br>
</div></form>
</div><center>
<div style="clear:both;width:100%;"><div id="scontent" style="overflow:auto;padding: 20px; text-align:left; vertical-align:top;"><font style="font-size:8pt;"><center>Waiting for search ...</center></font></div></div>